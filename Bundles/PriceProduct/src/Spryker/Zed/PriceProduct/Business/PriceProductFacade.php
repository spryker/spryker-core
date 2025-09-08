<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCollectionResponseTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface getRepository()
 */
class PriceProductFacade extends AbstractFacade implements PriceProductFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\PriceTypeTransfer>
     */
    public function getPriceTypeValues()
    {
        return $this->getFactory()
            ->createPriceTypeReader()
            ->getPriceTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findPriceBySku($sku, $priceTypeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return int|null
     */
    public function findPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findPriceFor($priceFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findPriceProductFor(PriceProductFilterTransfer $priceFilterTransfer): ?PriceProductTransfer
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findPriceProductFor($priceFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name)
    {
        return $this->getFactory()
            ->createPriceTypeWriter()
            ->createPriceType($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    public function findPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer
    {
        return $this->getFactory()
            ->createPriceTypeReader()
            ->findPriceTypeByName($priceTypeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     *
     * @return void
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct)
    {
        $this->getFactory()
            ->createWriterModel()
            ->setPriceForProduct($transferPriceProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()
            ->createInstaller()
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major. There is no use of this method in the core, and it wasn't updated for DMS ON mode usage.
     *
     * @param string $sku
     * @param string|null $priceType
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceType = null)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->hasValidPrice($sku, $priceType);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->hasValidPriceFor($priceFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->createPriceForProduct($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceTypeName()
    {
        return $this->getFactory()
            ->getConfig()
            ->getPriceTypeDefaultName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement. There is no use of this method, and it's not compatible with Dynamic Store mode.
     *
     * @param string $sku
     * @param string $priceType
     * @param string $currencyIsoCode
     *
     * @return int
     */
    public function getIdPriceProduct($sku, $priceType, $currencyIsoCode)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getProductPriceIdBySku($sku, $priceType, $currencyIsoCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function persistProductAbstractPriceCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createPriceProductAbstractWriter()
            ->persistProductAbstractPriceCollection($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createPriceProductConcreteWriter()
            ->persistProductConcretePriceCollection($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findPricesBySkuForCurrentStore($sku)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findPricesBySkuForCurrentStore($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return array<mixed>
     */
    public function findPricesBySkuGroupedForCurrentStore(
        string $sku,
        ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null
    ): array {
        return $this->getFactory()
            ->createPriceGrouper()
            ->findPricesBySkuGroupedForCurrentStore($sku, $priceProductDimensionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<mixed>
     */
    public function groupPriceProductCollection(array $priceProductTransfers)
    {
        return $this->getFactory()
            ->createPriceGrouper()
            ->groupPriceProduct($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPrices(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPricesById($idProductAbstract, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePrices(
        int $idProductConcrete,
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->getFactory()
            ->createReaderModel()
            ->findProductConcretePrices($idProductConcrete, $idProductAbstract, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed in the next major. There is no use of this method in the current code base, and it's not compatible with Dynamic Store mode.
     *
     * @param int $idAbstractProduct
     * @param string|null $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findProductAbstractPrice($idAbstractProduct, $priceTypeName = null)
    {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPrice($idAbstractProduct, $priceTypeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getPriceModeIdentifierForBothType()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getPriceModeIdentifierForBothType();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<mixed> $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string
    {
        return $this->getFactory()
            ->createPriceDataChecksumGenerator()
            ->generatePriceDataChecksum($priceData);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function persistPriceProductStore(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFactory()
            ->createPriceProductStoreWriter()
            ->persistPriceProductStore($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void
    {
        $this->getFactory()
            ->createPriceProductStoreWriter()
            ->deleteAllOrphanPriceProductStoreEntities();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtraction(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductCriteriaTransfer
    {
        return $this->getFactory()
            ->createProductCriteriaBuilder()
            ->buildCriteriaFromFilter($priceProductFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductConcretePricesWithoutPriceExtraction(
        int $idProductConcrete,
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->getFactory()
            ->createReaderModel()
            ->findProductConcretePricesWithoutPriceExtraction($idProductConcrete, $idProductAbstract, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdProductAbstractForPriceProduct(PriceProductTransfer $priceProductTransfer): ?int
    {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findIdProductAbstractForPriceProduct($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria(
        array $productAbstractIds,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->getFactory()
            ->createPriceProductAbstractReader()
            ->findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria($productAbstractIds, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductStore(PriceProductTransfer $priceProductTransfer): void
    {
        $this->getFactory()
            ->createPriceProductRemover()
            ->removePriceProductStore($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Business\PriceProductFacade::deletePriceProductCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductDefaultForPriceProduct(PriceProductTransfer $priceProductTransfer): void
    {
        $this->getFactory()
            ->createPriceProductDefaultRemover()
            ->removePriceProductDefaultsForPriceProduct($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionResponseTransfer
     */
    public function deletePriceProductCollection(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionResponseTransfer {
        return $this->getFactory()
            ->createPriceProductDeleter()
            ->deletePriceProductCollection($priceProductCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getValidPrices(array $priceProductFilterTransfers): array
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getValidPrices($priceProductFilterTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    public function isPriceProductByProductIdentifierAndPriceTypeExists(PriceProductTransfer $priceProductTransfer): bool
    {
        return $this->getRepository()
            ->isPriceProductByProductIdentifierAndPriceTypeExists($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\PriceProduct\Business\PriceProductFacade::expandProductConcreteTransfersWithPrices()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithPrices(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createPriceProductConcreteReader()
            ->expandProductConcreteWithPrices($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithPrices(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createPriceProductConcreteReader()
            ->expandProductConcreteTransfersWithPrices($productConcreteTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validatePrices(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        return $this->getFactory()
            ->createPriceProductValidator()
            ->validatePrices($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItem(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer
    {
        return $this->getFactory()
            ->createPriceProductWishlsitItemExpander()
            ->expandWishlistItem($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mergeProductAbstractPricesIntoProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        return $this->getFactory()
            ->createPriceProductExpander()
            ->mergeProductAbstractPricesIntoProductConcrete($productConcreteTransfer, $productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return array<int, array<\Generated\Shared\Transfer\PriceProductTransfer>>
     */
    public function findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findProductConcretePricesWithoutPriceExtractionByConcreteAbstractMap($priceProductCriteriaTransfer);
    }
}
