<?php

namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\AbstractEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ArticleEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\AssetEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\BlogEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CarrierServiceEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CheckoutEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CollectEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CommentEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CountryEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CustomCollectionEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CustomerAddressEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CustomerEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\CustomerSavedSearchEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\EventEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\FulFillmentEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\FulfillmentServiceEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\LocationEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\MetafieldEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\OrderEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\OrderRisksEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\PageEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\PolicyEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ProductImageEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ProductEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ProductVariantEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ProvinceEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\RecurringApplicationChargeEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\RedirectEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\RefundEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ScriptTagEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ShopEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\SmartCollectionEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\ThemeEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\TransactionEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\UserEndpoint;
use CodeCloud\Bundle\ShopifyBundle\Api\Endpoint\WebhookEndpoint;
use GuzzleHttp\ClientInterface;

/**
 * Main access point to Shopify Api.
 *
 * @property ArticleEndpoint Article
 * @property AssetEndpoint Asset
 * @property BlogEndpoint Blog
 * @property CarrierServiceEndpoint CarrierService
 * @property CheckoutEndpoint Checkout
 * @property CollectEndpoint Collect
 * @property CommentEndpoint Comment
 * @property CountryEndpoint Country
 * @property CustomCollectionEndpoint CustomCollection
 * @property CustomerAddressEndpoint CustomerAddress
 * @property CustomerEndpoint Customer
 * @property CustomerSavedSearchEndpoint SavedSearch
 * @property EventEndpoint Event
 * @property FulFillmentEndpoint Fulfillment
 * @property FulfillmentServiceEndpoint FulfillmentService
 * @property LocationEndpoint Location
 * @property MetafieldEndpoint Metafield
 * @property OrderEndpoint Order
 * @property OrderRisksEndpoint OrderRisks
 * @property PageEndpoint Page
 * @property PolicyEndpoint Policy
 * @property ProductImageEndpoint ProductImage
 * @property ProductEndpoint Product
 * @property ProductVariantEndpoint ProductVariant
 * @property ProvinceEndpoint Province
 * @property RecurringApplicationChargeEndpoint RecurringApplicationCharge
 * @property RedirectEndpoint Redirect
 * @property RefundEndpoint Refund
 * @property ScriptTagEndpoint ScriptTag
 * @property ShopEndpoint Shop
 * @property SmartCollectionEndpoint SmartCollection
 * @property ThemeEndpoint Theme
 * @property TransactionEndpoint Transaction
 * @property UserEndpoint User
 * @property WebhookEndpoint Webhook
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
    private $endpointClasses = [
        'Article' => ArticleEndpoint::class,
        'Asset' => AssetEndpoint::class,
        'Blog' => BlogEndpoint::class,
        'CarrierService' => CarrierServiceEndpoint::class,
        'Checkout' => CheckoutEndpoint::class,
        'Collect' => CollectEndpoint::class,
        'Comment' => CommentEndpoint::class,
        'Country' => CountryEndpoint::class,
        'CustomCollection' => CustomCollectionEndpoint::class,
        'CustomerAddress' => CustomerAddressEndpoint::class,
        'Customer' => CustomerEndpoint::class,
        'SavedSearch' => CustomerSavedSearchEndpoint::class,
        'Event' => EventEndpoint::class,
        'Fulfillment' => FulFillmentEndpoint::class,
        'FulfillmentService' => FulfillmentServiceEndpoint::class,
        'Location' => LocationEndpoint::class,
        'Metafield' => MetafieldEndpoint::class,
        'Order' => OrderEndpoint::class,
        'OrderRisks' => OrderRisksEndpoint::class,
        'Page' => PageEndpoint::class,
        'Policy' => PolicyEndpoint::class,
        'ProductImage' => ProductImageEndpoint::class,
        'Product' => ProductEndpoint::class,
        'ProductVariant' => ProductVariantEndpoint::class,
        'Province' => ProvinceEndpoint::class,
        'RecurringApplicationCharge' => RecurringApplicationChargeEndpoint::class,
        'Redirect' => RedirectEndpoint::class,
        'Refund' => RefundEndpoint::class,
        'ScriptTag' => ScriptTagEndpoint::class,
        'Shop' => ShopEndpoint::class,
        'SmartCollection' => SmartCollectionEndpoint::class,
        'Theme' => ThemeEndpoint::class,
        'Transaction' => TransactionEndpoint::class,
        'User' => UserEndpoint::class,
        'Webhook' => WebhookEndpoint::class,
    ];

    /**
     * Holds instantiated endpoints.
     *
     * @var AbstractEndpoint[]
     */
    private $endpoints;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $endpoint
     * @return AbstractEndpoint
     */
    public function getEndpoint($endpoint)
    {
        if (isset($this->endpoints[$endpoint])) {
            return $this->endpoints[$endpoint];
        }

        if (!isset($this->endpointClasses[$endpoint])) {
            throw new \InvalidArgumentException(sprintf('Endpoint %s does not exist', $endpoint));
        }

        $class = $this->endpointClasses[$endpoint];

        return $this->endpoints[$endpoint] = new $class($this->client);
    }

    public function __get($name)
    {
        return $this->getEndpoint($name);
    }
}
