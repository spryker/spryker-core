<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Base\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\ProductImageRepositoryInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;

class ProductImageWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\ProductImageRepositoryInterface $productImageRepository
     */
    public function __construct(
        protected MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository,
        protected ProductImageRepositoryInterface $productImageRepository
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveAbstractProductImages($dataSet);
        $this->saveConcreteProductImages($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveAbstractProductImages(DataSetInterface $dataSet): void
    {
        if (!$this->isProductAbstractSupported($dataSet)) {
            return;
        }

        /** @var array<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers */
        $productImageSetTransfers = $dataSet[ProductImageHydratorStep::DATA_ABSTRACT_PRODUCT_IMAGE_SET_TRANSFERS] ?? [];
        $idProductAbstract = $this->getProductAbstractId($dataSet);
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageSetTransfer->setIdProductAbstract($idProductAbstract);
            $spyProductImageSet = $this->persistProductImageSet($productImageSetTransfer);
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $this->persistImageWithImageSetRelation($spyProductImageSet, $productImageTransfer);
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveConcreteProductImages(DataSetInterface $dataSet): void
    {
        if (!$this->isProductConcreteSupported($dataSet)) {
            return;
        }

        /** @var array<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers */
        $productImageSetTransfers = $dataSet[ProductImageHydratorStep::DATA_CONCRETE_PRODUCT_IMAGE_SET_TRANSFERS] ?? [];
        $idProduct = $this->getProductId($dataSet);
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageSetTransfer->setIdProduct($idProduct);
            $spyProductImageSet = $this->persistProductImageSet($productImageSetTransfer);
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $this->persistImageWithImageSetRelation($spyProductImageSet, $productImageTransfer);
            }
        }
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $spyProductImageSet
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return void
     */
    protected function persistImageWithImageSetRelation(
        SpyProductImageSet $spyProductImageSet,
        ProductImageTransfer $productImageTransfer
    ): void {
        $spyProductImage = $this->persistProductImage($productImageTransfer);
        $spyProductImageSetToProductImage = $this->productImageRepository->getProductImageSetToProductImageRelationEntity(
            $spyProductImageSet->getIdProductImageSet(),
            $spyProductImage->getIdProductImage(),
        );

        $spyProductImageSetToProductImage->setSortOrder($productImageTransfer->getSortOrderOrFail());

        if (!$spyProductImageSetToProductImage->isNew() && !$spyProductImageSetToProductImage->isModified()) {
            return;
        }

        $spyProductImageSetToProductImage->save();

        $this->addImagePublishEvents($spyProductImageSet);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer): SpyProductImageSet
    {
        $productImageSetEntity = $this->productImageRepository->getProductImageSetEntity($productImageSetTransfer);

        if ($productImageSetEntity->isNew() || $productImageSetEntity->isModified()) {
            $productImageSetEntity->save();

            $this->addImagePublishEvents($productImageSetEntity);
        }

        return $productImageSetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\Base\SpyProductImage
     */
    protected function persistProductImage(ProductImageTransfer $productImageTransfer): SpyProductImage
    {
        $spyProductImage = $this->productImageRepository->getProductImageEntity($productImageTransfer);
        if ($spyProductImage->isNew() || $spyProductImage->isModified()) {
            $spyProductImage->save();
        }

        return $spyProductImage;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getProductAbstractId(DataSetInterface $dataSet): int
    {
        $abstractSku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU];

        return $this->merchantCombinedProductRepository->getIdProductAbstractByAbstractSku($abstractSku);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getProductId(DataSetInterface $dataSet): int
    {
        $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];

        return $this->merchantCombinedProductRepository->getIdProductBySku($sku);
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return void
     */
    protected function addImagePublishEvents(SpyProductImageSet $productImageSetEntity): void
    {
        if ($productImageSetEntity->getFkProductAbstract()) {
            DataImporterPublisher::addEvent(
                ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                $productImageSetEntity->getFkProductAbstract(),
            );
            DataImporterPublisher::addEvent(
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                $productImageSetEntity->getFkProductAbstract(),
            );
        }

        if ($productImageSetEntity->getFkProduct()) {
            DataImporterPublisher::addEvent(
                ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
                $productImageSetEntity->getFkProduct(),
            );
        }
    }
}
