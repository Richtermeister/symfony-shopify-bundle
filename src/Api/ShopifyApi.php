<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\AbstractResourceMapper;
use GuzzleHttp\ClientInterface;

/**
 * Main access point to Shopify Api.
 */
class ShopifyApi
{
	/**
	 * @var ClientInterface
	 */
	private $client;

    /**
     * @var string[]
     */
    private $mapperClasses;

    /**
     * @var AbstractResourceMapper[]
     */
    private $mappers;

    /**
     * @param ClientInterface $client
     * @param string[] $mapperClasses
     */
    public function __construct(ClientInterface $client, array $mapperClasses)
    {
        $this->client = $client;
        $this->mapperClasses = $mapperClasses;
    }

    /**
     * @param string $endpoint
     * @return AbstractResourceMapper
     */
    public function getEndpoint($endpoint)
    {
        if (isset($this->mappers[$endpoint])) {
            return $this->mappers[$endpoint];
        }

        if (!isset($this->mapperClasses[$endpoint])) {
            throw new \InvalidArgumentException(sprintf('Endpoint %s does not exist', $endpoint));
        }

        $class = $this->mapperClasses[$endpoint];

        return $this->mappers[$endpoint] = new $class($this->client);
    }
}
