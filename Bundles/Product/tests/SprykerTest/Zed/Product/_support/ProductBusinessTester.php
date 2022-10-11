<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductDeletedTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductBusinessTester extends Actor
{
    use _generated\ProductBusinessTesterActions;

    /**
     * @var array<int>
     */
    protected $productConcreteIds = [];

    /**
     * @var array<int>
     */
    protected $productAbstractIds = [];

    /**
     * @return void
     */
    public function setUpDatabase(): void
    {
        $this->insertProducts();

        $this->haveLocale([LocaleTransfer::LOCALE_NAME => 'en_US']);
        $this->haveLocale([LocaleTransfer::LOCALE_NAME => 'de_DE']);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return array<int>
     */
    public function getProductConcreteIds(): array
    {
        return $this->productConcreteIds;
    }

    /**
     * @return array<int>
     */
    public function getProductAbstractIds(): array
    {
        return $this->productAbstractIds;
    }

    /**
     * @return void
     */
    protected function insertProducts(): void
    {
        $productConcreteIds = [];
        $productAbstractIds = [];
        $productFacade = $this->getProductFacade();

        for ($i = 0; $i < 2; $i++) {
            $productAbstractTransfer = $this->createProductAbstractTransfer((string)$i);
            $productAbstractId = $productFacade->createProductAbstract($productAbstractTransfer);

            $productAbstractTransfer->setIdProductAbstract($productAbstractId);
            $productAbstractIds[] = $productAbstractId;

            foreach ($this->createProductConcreteTransferCollection($productAbstractTransfer) as $productConcreteTransfer) {
                $productConcreteIds[] = $productFacade->createProductConcrete($productConcreteTransfer);
            }
        }

        $this->productAbstractIds = $productAbstractIds;
        $this->productConcreteIds = $productConcreteIds;
    }

    /**
     * @return void
     */
    public function createProductUrls(): void
    {
        foreach ($this->productAbstractIds as $idProductAbstract) {
            foreach ($this->getLocaleFacade()->getAvailableLocales() as $idLocale => $localeName) {
                $this->haveUrl([
                    UrlTransfer::FK_LOCALE => $idLocale,
                    UrlTransfer::FK_RESOURCE_PRODUCT_ABSTRACT => $idProductAbstract,
                    UrlTransfer::URL => $this->getProductUrl($idProductAbstract, $localeName),
                ]);
            }
        }
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return string
     */
    public function getProductUrl(int $idProductAbstract, string $localeName): string
    {
        return sprintf(
            '/%s/product-' . $idProductAbstract,
            $localeName,
        );
    }

    /**
     * @param int $idLocale
     *
     * @return int
     */
    public function getUrlsCount(int $idLocale): int
    {
        return SpyUrlQuery::create()
            ->filterByFkLocale($idLocale)
            ->count();
    }

    /**
     * @return int
     */
    public function getProductConcreteDatabaseEntriesCount(): int
    {
        return (new SpyProductQuery())->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function deleteProductFromStore(
        ProductConcreteTransfer $productConcreteTransfer,
        StoreTransfer $storeTransfer
    ): int {
        return SpyProductAbstractStoreQuery::create()
            ->filterByFkProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->delete();
    }

    /**
     * @return bool
     */
    public function isPhp8(): bool
    {
        return version_compare(PHP_VERSION, '8.0.0', '>=');
    }

    /**
     * @return bool
     */
    public function isPhp81(): bool
    {
        return PHP_VERSION_ID >= 80100;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(string $sku): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setStoreRelation(
            $this->createStoreRelationTransfer($productAbstractTransfer),
        );
        $productAbstractTransfer->setSku('abstract_sku' . $sku);
        $productAbstractTransfer->setIsActive(true);
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject([$this->createLocalizedAttributeTransfer()]),
        );

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function createStoreRelationTransfer(ProductAbstractTransfer $productAbstractTransfer): StoreRelationTransfer
    {
        $storeTransfer = $this->getStoreFacade()->getCurrentStore();

        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity($productAbstractTransfer->getIdProductAbstract());
        $storeRelationTransfer->setIdStores([$storeTransfer->getIdStore()]);
        $storeRelationTransfer->setStores(
            new ArrayObject([$storeTransfer]),
        );

        return $storeRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function createProductConcreteTransferCollection(ProductAbstractTransfer $productAbstractTransfer): array
    {
        $productConcreteTransfers = [];

        for ($i = 0; $i < 2; $i++) {
            $productConcreteTransfer = (new ProductConcreteTransfer())
                ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
                ->setSku('concrete_sku_' . md5(uniqid()))
                ->setLocalizedAttributes(new ArrayObject([$this->createLocalizedAttributeTransfer()]))
                ->setIsActive(true);

            $productConcreteTransfers[] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributeTransfer(): LocalizedAttributesTransfer
    {
        $localizedAttributeTransfer = new LocalizedAttributesTransfer();
        $localizedAttributeTransfer->setName('concrete name');
        $localizedAttributeTransfer->setLocale($this->getLocaleFacade()->getCurrentLocale());

        return $localizedAttributeTransfer;
    }

    /**
     * @param array $skus
     *
     * @return void
     */
    public function deleteConcreteProductBySkus(array $skus): void
    {
        (new SpyProductQuery())->filterBySku_In($skus)->delete();
    }

    /**
     * @param array $skus
     *
     * @return int
     */
    public function countProductLocalizedAttributesByProductBySkus(array $skus): int
    {
        return (new SpyProductLocalizedAttributesQuery())
            ->useSpyProductQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->count();
    }

    /**
     * @return void
     */
    public function ensureProductAbstractTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductAbstractQuery());
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\Rule\InvokedCount
     */
    protected function once(): InvokedCountMatcher
    {
        return new InvokedCountMatcher(1);
    }

    /**
     * @param int $numberOfInvokations
     *
     * @return \PHPUnit\Framework\MockObject\Rule\InvokedCount
     */
    protected function exactly(int $numberOfInvokations): InvokedCountMatcher
    {
        return new InvokedCountMatcher($numberOfInvokations);
    }

    /**
     * @param callable $callback
     *
     * @return \PHPUnit\Framework\Constraint\Callback
     */
    protected static function callback(callable $callback): Callback
    {
        return new Callback($callback);
    }

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace|\PHPUnit\Framework\MockObject\MockObject $messageBrokerFacade
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string $messageType
     *
     * @return void
     */
    public function assertProductSuccessfullyPublishedViaMessageBroker(
        ProductToMessageBrokerInterfrace $messageBrokerFacade,
        ProductConcreteTransfer $productConcreteTransfer,
        string $messageType
    ): void {
        $storeReferences = [];
        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            if ($storeTransfer->getStoreReference()) {
                $storeReferences[] = $storeTransfer->getStoreReference();
            }
        }

        $messageBrokerFacade
            ->expects($this->exactly(2))
            ->method('sendMessage')
            ->with($this->callback(function ($message) use ($productConcreteTransfer, $messageType, $storeReferences) {
                $this->assertInstanceOf($messageType, $message);

                $productConcreteFromMessage = $message->getProductsConcrete()->offsetGet(0);

                $this->assertEquals(
                    $productConcreteTransfer->getIdProductConcrete(),
                    $productConcreteFromMessage->getIdProductConcrete(),
                );
                $this->assertEquals(
                    $productConcreteTransfer->getSku(),
                    $productConcreteFromMessage->getSku(),
                );
                $this->assertNotEmpty($message->getMessageAttributes()->getStoreReference());
                $this->assertContains($message->getMessageAttributes()->getStoreReference(), $storeReferences);

                return true;
            }));
    }

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace|\PHPUnit\Framework\MockObject\MockObject $messageBrokerFacade
     * @param string $productConcreteSku
     * @param string $messageType
     *
     * @return void
     */
    public function assertProductSuccessfullyUnpublishedViaMessageBroker(
        ProductToMessageBrokerInterfrace $messageBrokerFacade,
        string $productConcreteSku,
        string $messageType
    ): void {
        $stores = $this->getStoreFacade()->getAllStores();

        $messageBrokerFacade
            ->expects($this->exactly(count($stores)))
            ->method('sendMessage')
            ->with($this->callback(function (ProductDeletedTransfer $message) use ($productConcreteSku, $messageType) {
                $this->assertInstanceOf($messageType, $message);
                $this->assertEquals(
                    $productConcreteSku,
                    $message->getSku(),
                );

                return true;
            }));
    }
}
