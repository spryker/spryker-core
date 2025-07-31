<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\MerchantProductDataImport\Business\Exception\EntityNotFoundException;

class MerchantCombinedProductRepository implements MerchantCombinedProductRepositoryInterface
{
    /**
     * @var string
     */
    public const ID_PRODUCT = 'idProduct';

    /**
     * @var string
     */
    public const ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var string
     */
    public const ABSTRACT_SKU = 'abstractSku';

    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $resolvedProducts = [];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected static array $resolvedAbstractProducts = [];

    /**
     * @var array<string, bool> Keys are URLs, values are booleans indicating if the URL is available.
     */
    protected static array $urlAvailable = [];

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return void
     */
    public function addProductAbstract(SpyProductAbstract $productAbstractEntity): void
    {
        static::$resolvedAbstractProducts[$productAbstractEntity->getSku()] = [
            static::ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param string|null $abstractSku
     *
     * @return void
     */
    public function addProductConcrete(SpyProduct $productEntity, ?string $abstractSku = null): void
    {
        static::$resolvedProducts[$productEntity->getSku()] = [
            static::ID_PRODUCT => $productEntity->getIdProduct(),
            static::ABSTRACT_SKU => $abstractSku ?? $productEntity->getSpyProductAbstract()->getSku(),
        ];
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductBySku(string $sku): int
    {
        if (!isset(static::$resolvedProducts[$sku])) {
            $this->resolveProductByConcreteSku($sku);
        }

        return static::$resolvedProducts[$sku][static::ID_PRODUCT];
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductAbstractByAbstractSku(string $sku): int
    {
        if (!isset(static::$resolvedAbstractProducts[$sku])) {
            $this->resolveProductByAbstractSku($sku);
        }

        return static::$resolvedAbstractProducts[$sku][static::ID_PRODUCT_ABSTRACT];
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuByConcreteSku(string $sku): string
    {
        if (!isset(static::$resolvedAbstractProducts[$sku])) {
            $this->resolveProductByConcreteSku($sku);
        }

        return static::$resolvedAbstractProducts[$sku][static::ABSTRACT_SKU];
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findIdProductAbstractByAbstractSku(string $sku): ?int
    {
        try {
            return $this->getIdProductAbstractByAbstractSku($sku);
        } catch (EntityNotFoundException) {
            return null;
        }
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findIdProductBySku(string $sku): ?int
    {
        try {
            return $this->getIdProductBySku($sku);
        } catch (EntityNotFoundException) {
            return null;
        }
    }

    /**
     * @return array<string>
     */
    public function getSkuProductConcreteList(): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productCollection */
        $productCollection = SpyProductQuery::create()
            ->select([SpyProductTableMap::COL_SKU])
            ->find();

        return $productCollection->toArray();
    }

    /**
     * @param string $productUrl
     *
     * @return bool
     */
    public function isProductUrlAvailable(string $productUrl): bool
    {
        if (isset(static::$urlAvailable[$productUrl])) {
            return static::$urlAvailable[$productUrl];
        }

        $urlTaken = SpyUrlQuery::create()
            ->filterByUrl($productUrl)
            ->exists();

        static::$urlAvailable[$productUrl] = !$urlTaken;

        return static::$urlAvailable[$productUrl];
    }

    /**
     * @param string $productUrl
     *
     * @return void
     */
    public function markProductUrlUnavailable(string $productUrl): void
    {
        static::$urlAvailable[$productUrl] = false;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function resolveProductByConcreteSku(string $sku): void
    {
        $productEntity = SpyProductQuery::create()
            ->joinWithSpyProductAbstract()
            ->findOneBySku($sku);

        if (!$productEntity) {
            throw new EntityNotFoundException(sprintf('Concrete product by sku "%s" not found.', $sku));
        }

        static::$resolvedProducts[$sku] = [
            static::ID_PRODUCT => $productEntity->getIdProduct(),
            static::ABSTRACT_SKU => $productEntity->getSpyProductAbstract()->getSku(),
        ];
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function resolveProductByAbstractSku(string $sku): void
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->findOneBySku($sku);

        if (!$productAbstractEntity) {
            throw new EntityNotFoundException(sprintf('Abstract product by sku "%s" not found.', $sku));
        }

        static::$resolvedAbstractProducts[$sku] = [
            static::ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
        ];
    }
}
