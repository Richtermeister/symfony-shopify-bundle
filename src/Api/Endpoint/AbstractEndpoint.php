<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api\Endpoint;

use CodeCloud\Bundle\ShopifyBundle\Api\Request\Exception\FailedRequestException;
use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\ErrorResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\HtmlResponse;
use CodeCloud\Bundle\ShopifyBundle\Api\Response\JsonResponse;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Uri;

abstract class AbstractEndpoint
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @param ClientInterface $client
     * @param string $apiVersion
     */
    public function __construct(ClientInterface $client, $apiVersion = null)
    {
        $this->client = $client;
        $this->apiVersion = $apiVersion;
    }

    /**
     * @param RequestInterface $request
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
     * @throws FailedRequestException
     */
    protected function send(RequestInterface $request)
    {
        $request = $this->applyApiVersion($request);
        $response = $this->process($request);

        if (! $response->successful()) {
            throw new FailedRequestException('Failed request. ' . $response->getHttpResponse()->getReasonPhrase());
        }

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @param string $rootElement
     * @return array
     * @throws FailedRequestException
     */
    protected function sendPaged(RequestInterface $request, $rootElement)
    {
        return $this->apiVersion
            ? $this->processCursor($request, $rootElement)
            : $this->processPaged($request, $rootElement)
        ;
    }

    /**
     * @param array $items
     * @param GenericResource|null $prototype
     * @return array
     */
    protected function createCollection($items, GenericResource $prototype = null)
    {
        if (! $prototype) {
            $prototype = new GenericResource();
        }

        $collection = array();

        foreach ((array)$items as $item) {
            $newItem = clone $prototype;
            $newItem->hydrate($item);
            $collection[] = $newItem;
        }

        return $collection;
    }

    /**
     * @param array $data
     * @return GenericResource
     */
    protected function createEntity($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException(
                sprintf('Expected array, got "%s"', var_export($data, true)
            ));
        }

        $entity = new GenericResource();
        $entity->hydrate($data);

        return $entity;
    }

    /**
     * @param RequestInterface $request
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
     */
    protected function process(RequestInterface $request)
    {
        $guzzleResponse = $this->client->send($request);

        try {
            switch ($request->getHeaderLine('Content-type')) {
                case 'application/json':
                    $response = new JsonResponse($guzzleResponse);
                    break;
                default:
                    $response = new HtmlResponse($guzzleResponse);
            }
        } catch (ClientException $e) {
            $response = new ErrorResponse($guzzleResponse, $e);
        }

        return $response;
    }

    private function applyApiVersion(RequestInterface $request)
    {
        if (!$this->apiVersion) {
            return $request;
        }

        $uri = $request->getUri();
        $uri = $uri->withPath(str_replace("/admin/", "/admin/api/".$this->apiVersion."/", $uri->getPath()));
        return $request->withUri($uri);
    }

    /**
     * Loop through a set of API results that are available in pages, returning the full resultset as one array
     * @param RequestInterface $request
     * @param string $rootElement
     * @param array $params
     * @return array
     */
    protected function processPaged(RequestInterface $request, $rootElement, array $params = array())
    {
        $request = $this->applyApiVersion($request);

        if (empty($params['page'])) {
            $params['page'] = 1;
        }

        if (empty($params['limit'])) {
            $params['limit'] = 250;
        }

        $allResults = array();

        do {
            $requestUrl = $request->getUri();
            $paramDelim = strstr($requestUrl, '?') ? '&' : '?';

            $pagedRequest = $request->withUri(new Uri($requestUrl . $paramDelim . http_build_query($params)));

            $response = $this->process($pagedRequest);

            $root = $response->get($rootElement);

            if ($pageResults = empty($root) ? false : $root) {
                $allResults = array_merge($allResults, $pageResults);
            }

            $params['page']++;
        } while ($pageResults);

        return $allResults;
    }

    /**
     * Loop through a set of API results that are available in pages, returning the full resultset as one array
     * @param RequestInterface $request
     * @param string $rootElement
     * @return array
     */
    protected function processCursor(RequestInterface $request, $rootElement)
    {
        $request = $this->applyApiVersion($request);

        $allResults = array();

        do {
            $response = $this->process($request);

            $root = $response->get($rootElement);

            if (!empty($root)) {
                $allResults = array_merge($allResults, $root);
            }

            $linkHeader = $response->getHttpResponse()->getHeaderLine('Link');
            if (empty($linkHeader)) {
                return $allResults;
            }

            $links = extractLinks($linkHeader);
            if (empty($links['next'])) {
                return $allResults;
            }

            $request = $request->withUri(new Uri($links['next']));
        } while (true);

        return $allResults;
    }
}

// lovingly borrowed from: https://community.shopify.com/c/Shopify-APIs-SDKs/How-to-parse-Link-data-from-header-data-in-PHP/td-p/569537#
function extractLinks($linkHeader) {
    $cleanArray = [];

    if (strpos($linkHeader, ',') !== false) {
        //Split into two or more elements by comma
        $linkHeaderArr = explode(',', $linkHeader);
    } else {
        //Create array with one element
        $linkHeaderArr[] = $linkHeader;
    }

    foreach ($linkHeaderArr as $linkHeader) {
        $cleanArray += [
            extractRel($linkHeader) => extractLink($linkHeader)
        ];
    }
    return $cleanArray;
}

function extractLink($element) {
    if (preg_match('/<(.*?)>/', $element, $match) == 1) {
        return $match[1];
    }
    return null;
}

function extractRel($element) {
    if (preg_match('/rel="(.*?)"/', $element, $match) == 1) {
        return $match[1];
    }
    return null;
}
