<?php

namespace CodeCloud\Bundle\ShopifyBundle\EventListener;

use CodeCloud\Bundle\ShopifyBundle\Event\WebhookEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WebhookLoggerListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            WebhookEvent::NAME => 'onWebhook',
        ];
    }

    public function onWebhook(WebhookEvent $event)
    {
        $this->logger->info('Shopify Webhook Received', [
            'topic' => $event->getTopic(),
            'store' => $event->getStoreName(),
            'payload' => $event->getResource()->toArray(),
        ]);
    }
}
