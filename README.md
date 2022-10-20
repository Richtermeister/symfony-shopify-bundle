# Symfony Shopify Bundle

This bundle enables quick and easy integration with Shopify. Your app can be installed as private or public, and is easily embeddable within the Shopify admin area.

[![Build Status on Travis](https://img.shields.io/travis/Richtermeister/symfony-shopify-bundle/master.svg)](http://travis-ci.org/Richtermeister/symfony-shopify-bundle)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/Richtermeister/symfony-shopify-bundle.svg)](https://scrutinizer-ci.com/g/Richtermeister/symfony-shopify-bundle/)

## Features

* Shopify OAuth signup flow with a few configuration options.
* Thin wrapper around Guzzle for easy API interactions. All API endpoints are supported.
* Symfony firewall to verify incoming API requests are authenticated (to embed app in Shopify Admin)
* Webhook support to listen for Shopify events.

## The Store Model

Persistence is delegated to an implementation of `ShopifyStoreManagerInterface`. It is up to you to provide an implementation of it and handle persistence.

## OAUTH Configuration

```yaml
# config/packages/code_cloud.yaml
code_cloud_shopify:
    oauth:
        api_key: # your app's API Key
        shared_secret: # your app's shared secret  
        scope: # the scopes your app requires, i.e.: "read_customers,write_customers" 
        redirect_route: # the route to redirect users to after installing the app, i.e.: "admin_dashboard".. 
    webhooks:
        - orders/create
        - customers/update
        - app/uninstalled
    webhook_url: # (optional) a url where to direct Shopify webhooks
```

## API Usage

You can access the API of an authorized store via the `ShopifyApiFactory` service:
 
```php
$api = $factory->getForStore("name-of-store");

$customers = $api->Customer->findAll();
$orders = $api->Order->findAll();
```

## Webhooks

You can provide a list of webhooks you are interested in receiving, the bundle will automatically register them with Shopify.
By default the webhooks will be pointed at your app and a `WebhookEvent` is dispatched for every hook received.
If you provide a `webhook_url` you can point webhooks at a separate app, for example a Lambda.
This is highly encouraged, since for a noisy store the volume of Shopify events can overwhelm a simple LAMP stack. 

```php
<?php

namespace AppBundle\Event;

use CodeCloud\Bundle\ShopifyBundle\Event\WebhookEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WebhookListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            WebhookEvent::NAME => 'onWebhook',
        ];
    }

    public function onWebhook(WebhookEvent $event)
    {
        switch ($event->getTopic()) {
            case 'orders/create':
                // your custom logic here
                break;
            case 'orders/update':
                // your custom logic here
                break;
        }
    }
}

```

It is highly recommended to process webhooks via a queue to satisfy Shopify's response time requirements. 

## Security & Authentication

The bundle automatically resolves Shopify's JWT token to a session id. Since 3rd party cookies do not work (any more) within an iFrame, it is recommended to keep the session id in the url at all times.
The bundle has a routing listener to make this `shopify_session_id` parameter sticky, but all your admin routes need to explicitly include it, like so:

```php
/**
 * @Route("/admin/{shopify_session_id}")
 */
class AdminController extends AbstractController {}
```

Configure your `securiy.yaml`:

```yaml
security:
    providers:
        code_cloud:
            id: CodeCloud\Bundle\ShopifyBundle\Security\ShopifyAdminUserProvider

    firewalls:
        admin:
            pattern: ^/admin
            provider: code_cloud
            guard:
                authenticators:
                    - CodeCloud\Bundle\ShopifyBundle\Security\SessionAuthenticator
            stateless: true
    access_control:
        - { path: ^/admin, roles: ROLE_SHOPIFY_ADMIN }
        - { path: ^/, roles: PUBLIC_ACCESS }
```

Authenticated users will be an instance of `CodeCloud\Bundle\ShopifyBundle\Security\ShopifyAdminUser`,
their username will be the name of the authenticated store (storename.myshopify.com), and their roles will include `ROLE_SHOPIFY_ADMIN`.

For development purposes, you can impersonate any existing store without having to authenticate via Shopify.

```yaml
# in config_dev.yaml
code_cloud_shopify:
    dev_impersonate_store: "{your-store-name}.myshopify.com"
```

## Credits

Many thanks to [David Smith](http://code-cloud.uk) for originally creating this bundle.
