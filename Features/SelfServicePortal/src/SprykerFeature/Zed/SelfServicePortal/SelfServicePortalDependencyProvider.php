<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal;

use Orm\Zed\CompanyUser\Persistence\Base\SpyCompanyUserQuery;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SelfServicePortalDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_SERVICE_POINT = 'FACADE_SERVICE_POINT';

    /**
     * @var string
     */
    public const FACADE_SHIPMENT_TYPE = 'FACADE_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_EVENT = 'FACADE_EVENT';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER = 'FACADE_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_STOCK = 'FACADE_MERCHANT_STOCK';

    /**
     * @var string
     */
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @var string
     */
    public const FACADE_FILE_MANAGER = 'FACADE_FILE_MANAGER';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_STORAGE = 'FACADE_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const FACADE_COMPANY = 'FACADE_COMPANY';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_OFFER_SHIPMENT_TYPE = 'FACADE_PRODUCT_OFFER_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @var string
     */
    public const FACADE_STATE_MACHINE = 'FACADE_STATE_MACHINE';

    /**
     * @var string
     */
    public const FACADE_COMMENT = 'FACADE_COMMENT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_IMAGE = 'PROPEL_QUERY_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';

    /**
     * @var string
     */
    public const PROPEL_QUERY_SHIPMENT_TYPE = 'PROPEL_QUERY_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE = 'PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_FILE = 'PROPEL_QUERY_FILE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_FILE_INFO = 'PROPEL_QUERY_FILE_INFO';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STATE_MACHINE_ITEM_STATE = 'PROPEL_QUERY_STATE_MACHINE_ITEM_STATE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    /**
     * @var string
     */
    public const SERVICE_SELF_SERVICE_PORTAL = 'SERVICE_SELF_SERVICE_PORTAL';

    /**
     * @var string
     */
    public const PLUGINS_DASHBOARD_DATA_PROVIDER = 'PLUGINS_DASHBOARD_DATA_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_STATE_MACHINE_CONDITION = 'PLUGINS_STATE_MACHINE_CONDITION';

    /**
     * @var string
     */
    public const PLUGINS_STATE_MACHINE_COMMAND = 'PLUGINS_STATE_MACHINE_COMMAND';

    /**
     * @var string
     */
    public const PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER = 'PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addServicePointFacade($container);
        $container = $this->addProductOfferFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addMerchantStockFacade($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductImagePropelQuery($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addFileManagerFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addFileQuery($container);
        $container = $this->addFileInfoQuery($container);
        $container = $this->addMailFacade($container);
        $container = $this->addStateMachineFacade($container);
        $container = $this->addStateMachineConditionPlugins($container);
        $container = $this->addStateMachineCommandPlugins($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
        $container = $this->addSelfServicePortalService($container);

        return $container;
    }

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductOfferShipmentTypeFacade($container);
        $container = $this->addShipmentTypeFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addShipmentTypeQuery($container);
        $container = $this->addProductShipmentTypeQuery($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addServicePointFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addDashboardDataExpanderPlugins($container);
        $container = $this->addStateMachineFacade($container);
        $container = $this->addCommentFacade($container);
        $container = $this->addCompanyUserPropelQuery($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addFileManagerFacade($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addSspAssetManagementExpanderPlugins($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addProductPageSearchFacade($container);
        $container = $this->addProductStorageFacade($container);
        $container = $this->addMailFacade($container);
        $container = $this->addCustomerFacade($container);

        return $container;
    }

    public function providePersistenceLayerDependencies(Container $container): Container
    {
        parent::providePersistenceLayerDependencies($container);

        $container = $this->addOmsFacade($container);
        $container = $this->addProductQuery($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
        $container = $this->addFileQuery($container);
        $container = $this->addFileInfoQuery($container);
        $container = $this->addStateMachineItemStateQuery($container);
        $container = $this->addUtilDateTimeService($container);

        return $container;
    }

    protected function addShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT_TYPE, static function (Container $container): ShipmentTypeFacadeInterface {
            return $container->getLocator()->shipmentType()->facade();
        });

        return $container;
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, static function (Container $container): LocaleFacadeInterface {
            return $container->getLocator()->locale()->facade();
        });

        return $container;
    }

    protected function addServicePointFacade(Container $container): Container
    {
        $container->set(static::FACADE_SERVICE_POINT, static function (Container $container): ServicePointFacadeInterface {
            return $container->getLocator()->servicePoint()->facade();
        });

        return $container;
    }

    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, static function (Container $container): StoreFacadeInterface {
            return $container->getLocator()->store()->facade();
        });

        return $container;
    }

    protected function addEventFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT, static function (Container $container): EventFacadeInterface {
            return $container->getLocator()->event()->facade();
        });

        return $container;
    }

    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, static function (Container $container): EventBehaviorFacadeInterface {
            return $container->getLocator()->eventBehavior()->facade();
        });

        return $container;
    }

    protected function addProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER, static function (Container $container): ProductOfferFacadeInterface {
            return $container->getLocator()->productOffer()->facade();
        });

        return $container;
    }

    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, static function (Container $container): ProductFacadeInterface {
            return $container->getLocator()->product()->facade();
        });

        return $container;
    }

    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, static function (Container $container): MerchantFacadeInterface {
            return $container->getLocator()->merchant()->facade();
        });

        return $container;
    }

    protected function addMerchantStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_STOCK, static function (Container $container): MerchantStockFacadeInterface {
            return $container->getLocator()->merchantStock()->facade();
        });

        return $container;
    }

    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container): OmsFacadeInterface {
            return $container->getLocator()->oms()->facade();
        });

        return $container;
    }

    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container): MessengerFacadeInterface {
            return $container->getLocator()->messenger()->facade();
        });

        return $container;
    }

    protected function addProductOfferShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE, function (Container $container): ProductOfferShipmentTypeFacadeInterface {
            return $container->getLocator()->productOfferShipmentType()->facade();
        });

        return $container;
    }

    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container): TranslatorFacadeInterface {
            return $container->getLocator()->translator()->facade();
        });

        return $container;
    }

    protected function addCompanyFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY, function (Container $container): CompanyFacadeInterface {
            return $container->getLocator()->company()->facade();
        });

        return $container;
    }

    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container): CompanyBusinessUnitFacadeInterface {
            return $container->getLocator()->companyBusinessUnit()->facade();
        });

        return $container;
    }

    protected function addSequenceNumberFacade(Container $container): Container
    {
        $container->set(static::FACADE_SEQUENCE_NUMBER, function (Container $container): SequenceNumberFacadeInterface {
            return $container->getLocator()->sequenceNumber()->facade();
        });

        return $container;
    }

    protected function addFileManagerFacade(Container $container): Container
    {
        $container->set(static::FACADE_FILE_MANAGER, function (Container $container): FileManagerFacadeInterface {
            return $container->getLocator()->fileManager()->facade();
        });

        return $container;
    }

    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container): MailFacadeInterface {
            return $container->getLocator()->mail()->facade();
        });

        return $container;
    }

    protected function addStateMachineFacade(Container $container): Container
    {
        $container->set(static::FACADE_STATE_MACHINE, function (Container $container) {
            return $container->getLocator()->stateMachine()->facade();
        });

        return $container;
    }

    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }

    protected function addCommentFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMMENT, function (Container $container): CommentFacadeInterface {
            return $container->getLocator()->comment()->facade();
        });

        return $container;
    }

    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container): SalesFacadeInterface {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container): CompanyUserFacadeInterface {
            return $container->getLocator()->companyUser()->facade();
        });

        return $container;
    }

    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container): UserFacadeInterface {
            return $container->getLocator()->user()->facade();
        });

        return $container;
    }

    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, static function (Container $container): CustomerFacadeInterface {
            return $container->getLocator()->customer()->facade();
        });

        return $container;
    }

    protected function addSelfServicePortalService(Container $container): Container
    {
        $container->set(static::SERVICE_SELF_SERVICE_PORTAL, function (Container $container) {
            return $container->getLocator()->selfServicePortal()->service();
        });

        return $container;
    }

    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function (): SpyProductQuery {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    protected function addProductImagePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_IMAGE, $container->factory(function (): SpyProductImageQuery {
            return SpyProductImageQuery::create();
        }));

        return $container;
    }

    protected function addSalesOrderItemPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_ITEM, $container->factory(function (Container $container): SpySalesOrderItemQuery {
            return SpySalesOrderItemQuery::create();
        }));

        return $container;
    }

    protected function addShipmentTypeQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SHIPMENT_TYPE, $container->factory(function (Container $container): SpyShipmentTypeQuery {
            return SpyShipmentTypeQuery::create();
        }));

        return $container;
    }

    protected function addProductShipmentTypeQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE, $container->factory(function (Container $container): SpyProductShipmentTypeQuery {
            return SpyProductShipmentTypeQuery::create();
        }));

        return $container;
    }

    protected function addProductQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function (Container $container): SpyProductQuery {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    protected function addFileQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_FILE, $container->factory(function (): SpyFileQuery {
            return SpyFileQuery::create();
        }));

        return $container;
    }

    protected function addFileInfoQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_FILE_INFO, $container->factory(function (): SpyFileInfoQuery {
            return SpyFileInfoQuery::create();
        }));

        return $container;
    }

    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_USER, $container->factory(function (): SpyCompanyUserQuery {
            return SpyCompanyUserQuery::create();
        }));

        return $container;
    }

    protected function addStateMachineItemStateQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STATE_MACHINE_ITEM_STATE, $container->factory(function (): SpyStateMachineItemStateQuery {
            return SpyStateMachineItemStateQuery::create();
        }));

        return $container;
    }

    protected function addDashboardDataExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DASHBOARD_DATA_PROVIDER, function () {
            return $this->getDashboardDataExpanderPlugins();
        });

        return $container;
    }

    protected function addStateMachineConditionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STATE_MACHINE_CONDITION, function () {
            return $this->getStateMachineConditionPlugins();
        });

        return $container;
    }

    protected function addStateMachineCommandPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STATE_MACHINE_COMMAND, function () {
            return $this->getStateMachineCommandPlugins();
        });

        return $container;
    }

    protected function addSspAssetManagementExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER, function (Container $container) {
            return $this->getSspAssetManagementExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\SspAssetManagementExpanderPluginInterface>
     */
    protected function getSspAssetManagementExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<int, \SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\DashboardDataExpanderPluginInterface>
     */
    protected function getDashboardDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface>
     */
    protected function getStateMachineConditionPlugins(): array
    {
        return [];
    }

    protected function addProductPageSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_PAGE_SEARCH, function (Container $container): ProductPageSearchFacadeInterface {
            return $container->getLocator()->productPageSearch()->facade();
        });

        return $container;
    }

    protected function addProductStorageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_STORAGE, function (Container $container): ProductStorageFacadeInterface {
            return $container->getLocator()->productStorage()->facade();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
     */
    protected function getStateMachineCommandPlugins(): array
    {
        return [];
    }
}
