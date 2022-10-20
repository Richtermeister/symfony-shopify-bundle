<?php

namespace CodeCloud\Bundle\ShopifyBundle\EventListener;

use CodeCloud\Bundle\ShopifyBundle\Event\PostAuthEvent;
use CodeCloud\Bundle\ShopifyBundle\Service\WebhookCreatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InstallWebhooksListener implements EventSubscriberInterface
{
    /**
     * @var WebhookCreatorInterface
     */
    private $installer;

    /**
     * @var array
     */
    private $topics;

    public function __construct(WebhookCreatorInterface $installer, array $topics)
    {
        $this->installer = $installer;
        $this->topics = $topics;
    }

    public function installWebhooks(PostAuthEvent $event)
    {
        if (count($this->topics) == 0) {
            return;
        }

        $this->installer->createWebhooks($event->getShop(), $this->topics);
    }

    public static function getSubscribedEvents()
    {
        return [
            PostAuthEvent::class => 'installWebhooks',
        ];
    }
}
