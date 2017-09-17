<?php
namespace CodeCloud\Bundle\ShopifyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CodeCloudShopifyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('codecloud_shopify', $config);
        $container->setParameter('codecloud_shopify.oauth', $config['oauth']);
        $container->setParameter('codecloud_shopify.webhooks', $config['webhooks']);

        foreach ($config['oauth'] as $key => $value) {
            $container->setParameter('codecloud_shopify.oauth.'.$key, $value);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias('codecloud_shopify.store_manager', $config['store_manager_id']);
    }
}
