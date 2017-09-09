# Symfony Shopify Bundle

This bundle enables quick and easy integration with Shopify.

[![Build Status on Travis](https://img.shields.io/travis/Richtermeister/symfony-shopify-bundle/master.svg)](http://travis-ci.org/Richtermeister/symfony-shopify-bundle)

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

