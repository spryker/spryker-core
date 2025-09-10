<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOfferSearch;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider as ClientStoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;
use Spryker\Shared\MerchantProductOfferSearch\MerchantProductOfferSearchConfig;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\ProductPageSearch\MerchantNamesProductAbstractMapExpanderPlugin;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\ProductPageSearch\MerchantProductPageDataExpanderPlugin;
use Spryker\Zed\MerchantProductOfferSearch\Communication\Plugin\ProductPageSearch\MerchantProductPageDataLoaderPlugin;
use Spryker\Zed\MerchantProductOfferSearch\Dependency\Facade\MerchantProductOfferSearchToEventFacadeInterface;
use Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchDependencyProvider;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
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
class MerchantProductOfferSearchCommunicationTester extends Actor
{
    use _generated\MerchantProductOfferSearchCommunicationTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->addRabbitMqDependency();
        $this->addProductPageSearchDependencies();
        $this->mockSearchFacade();
        $this->addStoreClientMock();
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
            $productConcreteTransfer->getFkProductAbstract(),
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
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function setDependencyWithExpectedCall(int $idProductAbstract): void
    {
        $eventFacadeMock = Stub::makeEmpty(MerchantProductOfferSearchToEventFacadeInterface::class);
        $eventFacadeMock->expects(new InvokedCount(1))->method('triggerBulk')->with(MerchantProductOfferSearchConfig::PRODUCT_ABSTRACT_SEARCH_PUBLISH, [
            (new EventEntityTransfer())
                ->setId($idProductAbstract),
        ]);

        $this->setDependency(MerchantProductOfferSearchDependencyProvider::FACADE_EVENT, $eventFacadeMock);
    }

    /**
     * @param \SprykerTest\Zed\MerchantProductOfferSearch\bool|bool $isOffer1Active
     * @param \SprykerTest\Zed\MerchantProductOfferSearch\string|string $offer1ApprovalStatus
     *
     * @return list<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function haveProductAbstractMerchantData(
        bool $isOffer1Active = true,
        string $offer1ApprovalStatus = self::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED
    ): array {
        $productConcrete1 = $this->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $productConcrete2 = $this->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();

        $merchantTransfer = $this->haveMerchant([MerchantTransfer::IS_ACTIVE => true, MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);

        $this->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete1->getSku(),
            ProductOfferTransfer::IS_ACTIVE => $isOffer1Active,
            ProductOfferTransfer::APPROVAL_STATUS => $offer1ApprovalStatus,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $this->haveProductOffer([
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcrete2->getSku(),
            ProductOfferTransfer::IS_ACTIVE => true,
            ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
        ]);

        $productAbstractMerchantTransfer1 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete1->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchantTransfer->getName()]])
            ->setMerchantReferences([$storeTransfer->getName() => [$merchantTransfer->getMerchantReference()]]);

        $productAbstractMerchantTransfer2 = (new ProductAbstractMerchantTransfer())
            ->setIdProductAbstract($productConcrete2->getFkProductAbstract())
            ->setMerchantNames([$storeTransfer->getName() => [$merchantTransfer->getName()]])
            ->setMerchantReferences([$storeTransfer->getName() => [$merchantTransfer->getMerchantReference()]]);

        return [$productAbstractMerchantTransfer1, $productAbstractMerchantTransfer2];
    }

    /**
     * @return void
     */
    protected function addRabbitMqDependency(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->getLocator()->rabbitMq()->client()->createQueueAdapter(),
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
                new MerchantNamesProductAbstractMapExpanderPlugin(),
            ],
        );

        $this->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_LOADER,
            [
                new MerchantProductPageDataLoaderPlugin(),
            ],
        );

        $this->setDependency(
            ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER,
            [
                MerchantProductOfferSearchConfig::PLUGIN_PRODUCT_MERCHANT_DATA => new MerchantProductPageDataExpanderPlugin(),
            ],
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
            ],
        ));
    }

    /**
     * @return void
     */
    protected function addStoreClientMock(): void
    {
        $this->setDependency(ClientStoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
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
