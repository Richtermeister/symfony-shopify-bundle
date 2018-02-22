# Symfony Shopify Bundle

This bundle enables quick and easy integration with Shopify.

## Features

* Shopify OAuth signup flow with a few configuration options.
* Thin wrapper around Guzzle for easy API interactions. All API endpoints are supported.
* Symfony firewall to verify incoming API requests are authenticated (to embed app in Shopify Admin)
* Webhook support to listen for Shopify events.

## The Store Model

Stores are represented by instances of `ShopifyStoreInterface`. It is up to you to provide an implementation of it and handle persistence.

## OAUTH Configuration

``` yml
// app/confiy.yml

code_cloud_shopify:
    store_manager_id: { id of your store manager service }
    oauth:
        api_key: { your app's API Key }
        shared_secret: { your app's shared secret } 
        scope: { the scopes your app requires, i.e.: "read_customers,write_customers" }
        redirect_route: { the route to redirect users to after installing the app, i.e.: "admin_dashboard".. }
    webhooks:
        - orders/create
        - customers/update
```

## API Usage

You can access the API of an authorized store via the `` service:
 
``` php
// in Controller

$api = $this->get('')->getForStore("name-of-store");

$customers = $api->Customer->findAll();
$orders = $api->Order->findAll();
```

## Webhooks

You can register a list of webhooks you are interested in receiving. 
The bundle will automatically register them with Shopify and dispatch an event every time a webhook is received.

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

## Security & Authentication

By default, the bundle provides session-based authentication for admin areas embedded within Shopify.

```yaml
security:
    providers:
        codecloud_shopify:
            id: codecloud_shopify.security.admin_user_provider

    firewalls:
        admin:
            pattern: ^/admin
            provider: codecloud_shopify
            guard:
                authenticators:
                    - codecloud_shopify.security.session_authenticator
```

Authenticated users will be an instance of `CodeCloud\Bundle\ShopifyBundle\Security\ShopifyAdminUser`,
their username will be the name of the authenticated store (storename.myshopify.com), and their roles will include `ROLE_SHOPIFY_ADMIN`.

For development purposes, you can impersonate any existing store.

```yaml
# in config_dev.yml
code_cloud_shopify:
    dev_impersonate_store: "{store-name}.myshopify.com"
```

## Credits

Many thanks to [David Smith](http://code-cloud.uk) for originally creating this bundle.
