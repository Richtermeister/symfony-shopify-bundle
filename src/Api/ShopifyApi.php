<?php
namespace CodeCloud\Bundle\ShopifyBundle\Api;

use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\AbstractResourceMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ArticleMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\AssetMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\BlogMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CarrierServiceMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CheckoutMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CollectMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CommentMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CountryMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CustomCollectionMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CustomerAddressMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CustomerMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\CustomerSavedSearchMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\EventMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\FulFillmentMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\FulfillmentServiceMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\LocationMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\MetafieldMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\OrderMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\OrderRisksMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\PageMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\PolicyMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ProductImageMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ProductMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ProductVariantMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ProvinceMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\RecurringApplicationChargeMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\RedirectMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\RefundMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ShopMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\SmartCollectionMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\ThemeMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\TransactionMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\UserMapper;
use CodeCloud\Bundle\ShopifyBundle\Api\ResourceMapper\WebhookMapper;
use GuzzleHttp\ClientInterface;

/**
 * Main access point to Shopify Api.
 *
 * @property ArticleMapper Article
 * @property AssetMapper Asset
 * @property BlogMapper Blog
 * @property CarrierServiceMapper CarrierService
 * @property CheckoutMapper Checkout
 * @property CollectMapper Collect
 * @property CommentMapper Comment
 * @property CountryMapper Country
 * @property CustomCollectionMapper CustomCollection
 * @property CustomerAddressMapper CustomerAddress
 * @property CustomerMapper Customer
 * @property CustomerSavedSearchMapper SavedSearch
 * @property EventMapper Event
 * @property FulFillmentMapper Fulfillment
 * @property FulfillmentServiceMapper FulfillmentService
 * @property LocationMapper Location
 * @property MetafieldMapper Metafield
 * @property OrderMapper Order
 * @property OrderRisksMapper OrderRisks
 * @property PageMapper Page
 * @property PolicyMapper Policy
 * @property ProductImageMapper ProductImage
 * @property ProductMapper Product
 * @property ProductVariantMapper ProductVariant
 * @property ProvinceMapper Province
 * @property RecurringApplicationChargeMapper RecurringApplicationCharge
 * @property RedirectMapper Redirect
 * @property RefundMapper Refund
 * @property ShopMapper Shop
 * @property SmartCollectionMapper SmartCollection
 * @property ThemeMapper Theme
 * @property TransactionMapper Transaction
 * @property UserMapper User
 * @property WebhookMapper Webhook
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
    private $mapperClasses = [
        'Article' => ArticleMapper::class,
        'Asset' => AssetMapper::class,
        'Blog' => BlogMapper::class,
        'CarrierService' => CarrierServiceMapper::class,
        'Checkout' => CheckoutMapper::class,
        'Collect' => CollectMapper::class,
        'Comment' => CommentMapper::class,
        'Country' => CountryMapper::class,
        'CustomCollection' => CustomCollectionMapper::class,
        'CustomerAddress' => CustomerAddressMapper::class,
        'Customer' => CustomerMapper::class,
        'SavedSearch' => CustomerSavedSearchMapper::class,
        'Event' => EventMapper::class,
        'Fulfillment' => FulFillmentMapper::class,
        'FulfillmentService' => FulfillmentServiceMapper::class,
        'Location' => LocationMapper::class,
        'Metafield' => MetafieldMapper::class,
        'Order' => OrderMapper::class,
        'OrderRisks' => OrderRisksMapper::class,
        'Page' => PageMapper::class,
        'Policy' => PolicyMapper::class,
        'ProductImage' => ProductImageMapper::class,
        'Product' => ProductMapper::class,
        'ProductVariant' => ProductVariantMapper::class,
        'Province' => ProvinceMapper::class,
        'RecurringApplicationCharge' => RecurringApplicationChargeMapper::class,
        'Redirect' => RedirectMapper::class,
        'Refund' => RefundMapper::class,
        'Shop' => ShopMapper::class,
        'SmartCollection' => SmartCollectionMapper::class,
        'Theme' => ThemeMapper::class,
        'Transaction' => TransactionMapper::class,
        'User' => UserMapper::class,
        'Webhook' => WebhookMapper::class,
    ];

    /**
     * @var AbstractResourceMapper[]
     */
    private $mappers;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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

    public function __get($name)
    {
        return $this->getEndpoint($name);
    }
}
