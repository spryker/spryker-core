<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;

interface MerchantCombinedProductRepositoryInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return void
     */
    public function addProductAbstract(SpyProductAbstract $productAbstractEntity): void;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param string|null $abstractSku
     *
     * @return void
     */
    public function addProductConcrete(SpyProduct $productEntity, ?string $abstractSku = null): void;

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductBySku(string $sku): int;

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getIdProductAbstractByAbstractSku(string $sku): int;

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findIdProductAbstractByAbstractSku(string $sku): ?int;

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findIdProductBySku(string $sku): ?int;

    /**
     * @return array<string>
     */
    public function getSkuProductConcreteList(): array;

    /**
     * @param string $sku
     *
     * @return string
     */
    public function getAbstractSkuByConcreteSku(string $sku): string;

    /**
     * @param string $productUrl
     *
     * @return bool
     */
    public function isProductUrlAvailable(string $productUrl): bool;

    /**
     * @param string $productUrl
     *
     * @return void
     */
    public function markProductUrlUnavailable(string $productUrl): void;
}
