<?php

namespace CodeCloud\Bundle\ShopifyBundle\Service;

use CodeCloud\Bundle\ShopifyBundle\Api\GenericResource;
use CodeCloud\Bundle\ShopifyBundle\Api\ShopifyApiFactory;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Creates EventBridge Webhooks.
 */
class WebhookCreatorEventBridge implements WebhookCreatorInterface
{
    /**
     * @var ShopifyApiFactory
     */
    private $apis;

    /**
     * @var string
     */
    private $eventBridgeArn;

    public function __construct(ShopifyApiFactory $apis, string $eventBridgeArn)
    {
        $this->apis = $apis;
        $this->eventBridgeArn = $eventBridgeArn;
    }

    /**
     * {@inheritdoc}
     */
    public function createWebhooks(string $storeName, array $topics)
    {
        $api = $this->apis->getForStore($storeName);

        foreach ($topics as $topic) {
            $webhook = GenericResource::create([
                'topic' => $topic,
                'address' => $this->eventBridgeArn,
                'format' => 'json',
            ]);

            try {
                $api->Webhook->create($webhook);
            } catch (ClientException $e) {
                if ($e->getResponse()->getStatusCode() == 422) {
                    continue;
                }

                throw $e;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listWebhooks(string $storeName)
    {
        $api = $this->apis->getForStore($storeName);

        return $api->Webhook->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAllWebhooks(string $storeName)
    {
        $api = $this->apis->getForStore($storeName);

        foreach ($api->Webhook->findAll() as $webhook) {
            $api->Webhook->delete($webhook['id']);
        }
    }
}
