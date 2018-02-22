<?php

namespace CodeCloud\Bundle\ShopifyBundle\EventListener;

use CodeCloud\Bundle\ShopifyBundle\Event\PostAuthEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Listens to `PostAuth` event and stores authenticated shop in the session (if available).
 */
class SessionAuthenticationListener implements EventSubscriberInterface
{
    CONST SESSION_PARAMETER = 'codecloud_shopify.authorized_store';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onPostAuth(PostAuthEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();

        if (!$request->hasSession()) {
            return;
        }

        $request->getSession()->set(self::SESSION_PARAMETER, $event->getShop());
    }

    public static function getSubscribedEvents()
    {
        return [
            PostAuthEvent::NAME => 'onPostAuth',
        ];
    }
}
