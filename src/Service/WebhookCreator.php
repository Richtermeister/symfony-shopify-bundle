<?php

namespace CodeCloud\Bundle\ShopifyBundle\Service;

use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;
use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiFactory;
use CodeCloud\Bundle\ShopifyBundle\Model\ShopifyStoreInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Creates Webhooks.
 */
class WebhookCreator
{
    /**
     * @var ShopifyApiFactory
     */
    private $apis;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @param ShopifyApiFactory $apis
     * @param UrlGeneratorInterface $router
     */
    public function __construct(ShopifyApiFactory $apis, UrlGeneratorInterface $router)
    {
        $this->apis = $apis;
        $this->router = $router;
    }

    /**
     * @param ShopifyStoreInterface $store
     * @param array $topics
     */
    public function createWebhooks(ShopifyStoreInterface $store, array $topics)
    {
        $api = $this->apis->getForStore($store->getStoreName());

        foreach ($topics as $topic) {
            $endpoint = $this->router->generate('codecloud_shopify_webhooks', [
                'store' => $store->getStoreName(),
                'topic' => $topic,
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $webhook = GenericResource::create([
                'topic' => $topic,
                'address' => $endpoint,
                'format' => 'json',
            ]);

            $api->Webhook->create($webhook);
        }
    }

    /**
     * @param ShopifyStoreInterface $store
     * @return array|\CodeCloud\Bundle\ShopifyBundle\Api\GenericResource[]
     */
    public function listWebhooks(ShopifyStoreInterface $store)
    {
        $api = $this->apis->getForStore($store->getStoreName());

        return $api->Webhook->findAll();
    }

    /**
     * @param ShopifyStoreInterface $store
     */
    public function deleteAllWebhooks(ShopifyStoreInterface $store)
    {
        $api = $this->apis->getForStore($store->getStoreName());

        foreach ($api->Webhook->findAll() as $webhook) {
            $api->Webhook->delete($webhook['id']);
        }
    }
}
