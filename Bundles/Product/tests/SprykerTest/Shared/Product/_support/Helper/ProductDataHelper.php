<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Product\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Stock\Helper\StockDataHelper;
use SprykerTest\Shared\Tax\Helper\TaxSetDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;

class ProductDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;
    use ModuleLocatorTrait;

    /**
     * @var \SprykerTest\Shared\Tax\Helper\TaxSetDataHelper
     */
    protected $taxSetDataHelper;

    /**
     * @var \SprykerTest\Shared\Tax\Helper\TaxSetDataHelper
     */
    protected $stockDataHelper;

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct(array $productConcreteOverride = [], array $productAbstractOverride = []): ProductConcreteTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProductId]))
            ->seed($productConcreteOverride)
            ->build();

        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productFacade->createProductConcrete($productConcreteTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d, Concrete Product: %d',
            $abstractProductId,
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer) {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function haveProductAbstract(array $productAbstractOverride = []): ProductAbstractTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d',
            $abstractProductId
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractTransfer) {
            $this->cleanupProductAbstract($productAbstractTransfer->getIdProductAbstract());
        });

        return $productAbstractTransfer;
    }

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveFullProduct(
        array $productConcreteOverride = [],
        array $productAbstractOverride = []
    ): ProductConcreteTransfer {
        $allStoresRelation = $this->getAllStoresRelation()->toArray();

        $localizedAttributes = (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::NAME => uniqid('Product #', true),
        ]))->withLocale($this->getCurrentLocale()->toArray())->build()->toArray();

        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))
            ->withLocalizedAttributes($localizedAttributes)
            ->withStoreRelation($allStoresRelation)
            ->build();

        $productFacade = $this->getProductFacade();

        $idProductAbstract = $productFacade->createProductAbstract($productAbstractTransfer);
        $productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assignTaxSetToProductAbstract($productAbstractTransfer);

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = (new ProductConcreteBuilder(array_merge(['fkProductAbstract' => $idProductAbstract], $productConcreteOverride)))
            ->withLocalizedAttributes($localizedAttributes)
            ->withStores($allStoresRelation)
            ->build();
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());

        $productFacade->createProductConcrete($productConcreteTransfer);

        $this->assignProductConcreteToStock($productConcreteTransfer);

        $productFacade->createProductUrl(
            $productAbstractTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
        );

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d, Concrete Product: %d',
            $idProductAbstract,
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer) {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @return \SprykerTest\Shared\Tax\Helper\TaxSetDataHelper|null
     */
    protected function findTaxSetDataHelper(): ?TaxSetDataHelper
    {
        $this->taxSetDataHelper = $this->taxSetDataHelper ?: $this->findModule(TaxSetDataHelper::class);

        return $this->taxSetDataHelper;
    }

    /**
     * @return \SprykerTest\Shared\Stock\Helper\StockDataHelper|null
     */
    protected function findStockDataHelper(): ?StockDataHelper
    {
        $this->stockDataHelper = $this->stockDataHelper ?: $this->findModule(StockDataHelper::class);

        return $this->stockDataHelper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assignTaxSetToProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $taxSetDataHelper = $this->findTaxSetDataHelper();

        if (!$taxSetDataHelper) {
            return;
        }

        $taxSetTransfer = $taxSetDataHelper->haveTaxSet();
        $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());
        $this->getLocator()
            ->taxProductConnector()
            ->facade()
            ->saveTaxSetToProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assignProductConcreteToStock(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $stockDataHelper = $this->findStockDataHelper();

        if (!$stockDataHelper) {
            return;
        }

        $stockDataHelper->haveProductInStock([StockProductTransfer::SKU => $productConcreteTransfer->getSku()]);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getAllStoresRelation(): StoreRelationTransfer
    {
        $stores = $this->getStoreFacade()->getAllStores();
        $idStores = array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $stores);

        /** @var \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer */
        $storeRelationTransfer = (new StoreRelationBuilder([
            StoreRelationTransfer::ID_STORES => $idStores,
            StoreRelationTransfer::STORES => new ArrayObject($stores),
        ]))->build();

        return $storeRelationTransfer;
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductAbstract(ProductAbstractTransfer $productAbstractTransfer, array $localizedAttributes): void
    {
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes)
        );

        $this->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductConcrete(ProductConcreteTransfer $productConcreteTransfer, array $localizedAttributes): void
    {
        $productConcreteTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes)
        );

        $this->getProductFacade()->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    private function getProductQuery()
    {
        return $this->getLocator()->product()->queryContainer();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    private function cleanupProductConcrete($idProductConcrete)
    {
        $this->debug(sprintf('Deleting Concrete Product: %d', $idProductConcrete));

        $this->getProductQuery()
            ->queryProduct()
            ->findByIdProduct($idProductConcrete)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    private function cleanupProductAbstract($idProductAbstract)
    {
        $this->debug(sprintf('Deleting Abstract Product: %d', $idProductAbstract));

        $this->getProductQuery()
            ->queryProductAbstract()
            ->findByIdProductAbstract($idProductAbstract)
            ->delete();
    }
}
