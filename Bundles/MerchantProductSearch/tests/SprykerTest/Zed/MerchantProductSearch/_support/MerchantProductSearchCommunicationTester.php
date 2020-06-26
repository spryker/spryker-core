<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductSearch;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\MerchantProductSearch\MerchantProductSearchConfig;
use Spryker\Zed\MerchantProductSearch\Communication\Plugin\ProductPageSearch\MerchantProductAbstractMapExpanderPlugin;
use Spryker\Zed\MerchantProductSearch\Communication\Plugin\ProductPageSearch\MerchantProductPageDataExpanderPlugin;
use Spryker\Zed\MerchantProductSearch\Communication\Plugin\ProductPageSearch\MerchantProductPageDataLoaderPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductSearchCommunicationTester extends Actor
{
    use _generated\MerchantProductSearchCommunicationTesterActions;

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->addRabbitMqDependency();
        $this->addProductPageSearchDependencies();
        $this->mockSearchFacade();
    }

    /**
     * @return \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery
     */
    public function getProductAbstractPageSearchPropelQuery(): SpyProductAbstractPageSearchQuery
    {
        return SpyProductAbstractPageSearchQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function addProductRelatedData(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productAbstractTransfer = $this->getProductFacade()->findProductAbstractById(
            $productConcreteTransfer->getFkProductAbstract()
        );

        $localizedAttributeTransfers = $this->generateLocalizedAttributes();

        $this->addLocalizedAttributesToProductAbstract($productAbstractTransfer, $localizedAttributeTransfers);
        $this->addStoreRelationToProductAbstracts($productAbstractTransfer);
        $this->addLocalizedAttributesToProductConcrete($productConcreteTransfer, $localizedAttributeTransfers);

        $localeTransfer = $this->getLocator()->locale()->facade()->getCurrentLocale();
        $categoryTransfer = $this->haveLocalizedCategory(['locale' => $localeTransfer]);
        $this->getLocator()
            ->productSearch()
            ->facade()
            ->activateProductSearch($productConcreteTransfer->getIdProductConcrete(), [$localeTransfer]);

        $productIdsToAssign = [$productAbstractTransfer->getIdProductAbstract()];

        $this->addProductToCategoryMappings($categoryTransfer->getIdCategory(), $productIdsToAssign);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function assertProductPageAbstractSearch(MerchantTransfer $merchantTransfer, ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productPageSearchEntity = $this->getProductAbstractPageSearchPropelQuery()
            ->orderByIdProductAbstractPageSearch()
            ->findOneByFkProductAbstract($productConcreteTransfer->getFkProductAbstract());

        $this->assertNotNull($productPageSearchEntity);

        $data = $productPageSearchEntity->getStructuredData();
        $decodedData = json_decode($data, true);

        foreach ($productConcreteTransfer->getStores() as $storeTransfer) {
            $this->assertContains($merchantTransfer->getName(), $decodedData['merchant_names'][$storeTransfer->getName()]);
        }
    }

    /**
     * @return void
     */
    protected function addRabbitMqDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    protected function addProductPageSearchDependencies(): void
    {
        $this->setDependency(
            ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_MAP_EXPANDER,
            [
                new MerchantProductAbstractMapExpanderPlugin(),
            ]
        );

        $this->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_LOADER,
            [
                new MerchantProductPageDataLoaderPlugin(),
            ]
        );

        $this->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER,
            [
                MerchantProductSearchConfig::PLUGIN_MERCHANT_PRODUCT_DATA => new MerchantProductPageDataExpanderPlugin(),
            ]
        );
    }

    /**
     * @return void
     */
    protected function mockSearchFacade(): void
    {
        $this->setDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH, Stub::make(
            ProductPageSearchToSearchBridge::class,
            [
                'transformPageMapToDocumentByMapperName' => function () {
                    return [];
                },
            ]
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function addStoreRelationToProductAbstracts(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $storeIds = $this->getStoreIds();

        $productAbstractTransfer->setStoreRelation((new StoreRelationTransfer())->setIdStores($storeIds));

        $this->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @return array
     */
    protected function getStoreIds(): array
    {
        $storeIds = [];

        foreach ($this->getLocator()->store()->facade()->getAllStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @return void
     */
    protected function addProductToCategoryMappings(int $idCategory, array $productIdsToAssign): void
    {
        $this->getLocator()
            ->productCategory()
            ->facade()
            ->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }
}
