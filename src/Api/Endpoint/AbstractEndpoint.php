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
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     * @return \CodeCloud\Bundle\ShopifyBundle\Api\Response\ResponseInterface
     * @throws FailedRequestException
     */
    protected function send(RequestInterface $request)
    {
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
        return $this->processPaged($request, $rootElement);
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

    /**
     * Loop through a set of API results that are available in pages, returning the full resultset as one array
     * @param RequestInterface $request
     * @param string $rootElement
     * @param array $params
     * @return array
     */
    protected function processPaged(RequestInterface $request, $rootElement, array $params = array())
    {
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
}
