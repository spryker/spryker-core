<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    /**
     * @var array<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet>
     */
    protected array $resolvedProductImageSets = [];

    /**
     * @var array<\Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage>
     */
    protected array $resolvedProductImageSetToProductImageRelations = [];

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    public function getProductImageSetEntity(
        ProductImageSetTransfer $productImageSetTransfer
    ): SpyProductImageSet {
        $key = $productImageSetTransfer->getProductImageSetKey() ?? $this->buildProductImageSetKey(
            $productImageSetTransfer->getNameOrFail(),
            $productImageSetTransfer->getLocale()?->getIdLocale(),
            $productImageSetTransfer->getIdProductAbstract(),
            $productImageSetTransfer->getIdProduct(),
        );

        if (!isset($this->resolvedProductImageSets[$key])) {
            $this->resolvedProductImageSets[$key] = $this->getProductImageSet(
                $productImageSetTransfer->getNameOrFail(),
                $productImageSetTransfer->getLocale()?->getIdLocale(),
                $productImageSetTransfer->getIdProductAbstract(),
                $productImageSetTransfer->getIdProduct(),
                $key,
            );
        }

        return $this->resolvedProductImageSets[$key];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    public function getProductImageEntity(ProductImageTransfer $productImageTransfer): SpyProductImage
    {
        $spyProductImage = new SpyProductImage();
        $spyProductImage->fromArray($productImageTransfer->toArray());

        return $spyProductImage;
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    public function getProductImageSetToProductImageRelationEntity(
        int $productImageSetId,
        int $productImageId
    ): SpyProductImageSetToProductImage {
        $key = $this->buildProductImageSetToProductImageRelationKey($productImageSetId, $productImageId);

        if (!isset($this->resolvedProductImageSetToProductImageRelations[$key])) {
            $this->resolvedProductImageSetToProductImageRelations[$key] = $this->getProductImageSetToProductImageRelation($productImageSetId, $productImageId);
        }

        return $this->resolvedProductImageSetToProductImageRelations[$key];
    }

    /**
     * @param string $productImageKey
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    protected function getProductImage(string $productImageKey): SpyProductImage
    {
        $productImageEntity = SpyProductImageQuery::create()
            ->filterByProductImageKey($productImageKey)
            ->findOne();

        if ($productImageEntity) {
            return $productImageEntity;
        }

        return new SpyProductImage();
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return string
     */
    protected function buildProductImageSetToProductImageRelationKey(
        int $productImageSetId,
        int $productImageId
    ): string {
        return sprintf('%d:%d', $productImageSetId, $productImageId);
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    protected function getProductImageSetToProductImageRelation(
        int $productImageSetId,
        int $productImageId
    ): SpyProductImageSetToProductImage {
        return SpyProductImageSetToProductImageQuery::create()
            ->filterByFkProductImageSet($productImageSetId)
            ->filterByFkProductImage($productImageId)
            ->findOneOrCreate();
    }

    /**
     * @param string $name
     * @param int|null $localeId
     * @param int|null $productAbstractId
     * @param int|null $productConcreteId
     *
     * @return string
     */
    protected function buildProductImageSetKey(
        string $name,
        ?int $localeId,
        ?int $productAbstractId = null,
        ?int $productConcreteId = null
    ): string {
        return sprintf(
            '%s:%d:%d:%d',
            $name,
            $localeId ?? 0,
            $productAbstractId ?? 0,
            $productConcreteId ?? 0,
        );
    }

    /**
     * @param string $name
     * @param int|null $localeId
     * @param int|null $productAbstractId
     * @param int|null $productConcreteId
     * @param string|null $productImageSetKey
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected function getProductImageSet(
        string $name,
        ?int $localeId,
        ?int $productAbstractId = null,
        ?int $productConcreteId = null,
        ?string $productImageSetKey = null
    ): SpyProductImageSet {
        $query = SpyProductImageSetQuery::create()
            ->filterByName($name)
            ->filterByFkLocale($localeId);

        if ($productAbstractId) {
            $query->filterByFkProductAbstract($productAbstractId);
        }

        if ($productConcreteId) {
            $query->filterByFkProduct($productConcreteId);
        }

        if ($productImageSetKey) {
            $query->filterByProductImageSetKey($productImageSetKey);
        }

        return $query->findOneOrCreate();
    }
}
