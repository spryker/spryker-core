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
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductBusinessTester extends Actor
{
    use _generated\ProductBusinessTesterActions;

    /**
     * @var int[]
     */
    protected $productConcreteIds = [];

    /**
     * @var int[]
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
     * @return int[]
     */
    public function getProductConcreteIds(): array
    {
        return $this->productConcreteIds;
    }

    /**
     * @return int[]
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
            $localeName
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
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(string $sku): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setStoreRelation(
            $this->createStoreRelationTransfer($productAbstractTransfer)
        );
        $productAbstractTransfer->setSku('abstract_sku' . $sku);
        $productAbstractTransfer->setIsActive(true);
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject([$this->createLocalizedAttributeTransfer()])
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
            new ArrayObject([$storeTransfer])
        );

        return $storeRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
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
}
