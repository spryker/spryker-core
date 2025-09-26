<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal;

use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query\SspAssetSearchQueryPlugin;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_SHIPMENT_TYPE_STORAGE = 'CLIENT_SHIPMENT_TYPE_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const CLIENT_PERMISSION = 'CLIENT_PERMISSION';

    /**
     * @var string
     */
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_STORAGE = 'CLIENT_PRODUCT_OFFER_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE = 'CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @var string
     */
    public const PLUGIN_SSP_ASSET_SEARCH_QUERY = 'PLUGIN_SSP_ASSET_SEARCH_QUERY';

    /**
     * @var string
     */
    public const PLUGINS_SSP_ASSET_SEARCH_RESULT_FORMATTER = 'PLUGINS_SSP_ASSET_SEARCH_RESULT_FORMATTER';

    /**
     * @var string
     */
    public const PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER = 'PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addZedRequestClient($container);
        $container = $this->addShipmentTypeStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addPermissionClient($container);
        $container = $this->addSearchClient($container);
        $container = $this->addSspAssetSearchQueryPlugin($container);
        $container = $this->addSspAssetSearchResultFormatterPlugins($container);
        $container = $this->addSspAssetSearchQueryExpanderPlugins($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addProductOfferStorageClient($container);
        $container = $this->addProductOfferAvailabilityStorageClient($container);

        return $container;
    }

    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, static function (Container $container): ZedRequestClientInterface {
            return $container->getLocator()->zedRequest()->client();
        });

        return $container;
    }

    protected function addShipmentTypeStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHIPMENT_TYPE_STORAGE, static function (Container $container): ShipmentTypeStorageClientInterface {
            return $container->getLocator()->shipmentTypeStorage()->client();
        });

        return $container;
    }

    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container): StoreClientInterface {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return $container->getLocator()->storage()->client();
        });

        return $container;
    }

    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return $container->getLocator()->synchronization()->service();
        });

        return $container;
    }

    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }

    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    protected function addPermissionClient(Container $container): Container
    {
        $container->set(static::CLIENT_PERMISSION, function (Container $container): PermissionClientInterface {
            return $container->getLocator()->permission()->client();
        });

        return $container;
    }

    protected function addCompanyUserClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_USER, function (Container $container): CompanyUserClientInterface {
            return $container->getLocator()->companyUser()->client();
        });

        return $container;
    }

    protected function addSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return $container->getLocator()->search()->client();
        });

        return $container;
    }

    protected function addProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return $container->getLocator()->productOfferStorage()->client();
        });

        return $container;
    }

    protected function addProductOfferAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE, function (Container $container) {
            return $container->getLocator()->productOfferAvailabilityStorage()->client();
        });

        return $container;
    }

    protected function addSspAssetSearchQueryPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SSP_ASSET_SEARCH_QUERY, function (): QueryInterface {
            return $this->createSspAssetSearchQueryPlugin();
        });

        return $container;
    }

    protected function addSspAssetSearchResultFormatterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SSP_ASSET_SEARCH_RESULT_FORMATTER, function (): array {
            return $this->getSspAssetSearchResultFormatterPlugins();
        });

        return $container;
    }

    protected function addSspAssetSearchQueryExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER, function (): array {
            return $this->getSspAssetSearchQueryExpanderPlugins();
        });

        return $container;
    }

    protected function createSspAssetSearchQueryPlugin(): QueryInterface
    {
        return new SspAssetSearchQueryPlugin();
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>
     */
    protected function getSspAssetSearchResultFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface>
     */
    protected function getSspAssetSearchQueryExpanderPlugins(): array
    {
        return [];
    }

    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return $container->getLocator()->quote()->client();
        });

        return $container;
    }
}
