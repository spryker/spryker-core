<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductToLocaleInterface $localeFacade,
        ProductRepositoryInterface $productRepository
    ) {
        $this->localeFacade = $localeFacade;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $sku
     * @param null|int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function filterProductAbstractBySku(string $sku, ?int $limit = null): array
    {
        return $this->productRepository
            ->filterProductAbstractBySku($sku, $limit);
    }

    /**
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function filterProductAbstractByLocalizedName(string $localizedName, ?int $limit = null): array
    {
        return $this->productRepository
            ->filterProductAbstractByLocalizedName(
                $this->getCurrentLocale(),
                $localizedName,
                $limit
            );
    }

    /**
     * @param string $sku
     * @param null|int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    public function filterProductConcreteBySku(string $sku, ?int $limit = null): array
    {
        return $this->productRepository
            ->filterProductConcreteBySku($sku, $limit);
    }

    /**
     * @param string $localizedName
     * @param null|int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    public function filterProductConcreteByLocalizedName(string $localizedName, ?int $limit = null): array
    {
        return $this->productRepository
            ->filterProductConcreteByLocalizedName(
                $this->getCurrentLocale(),
                $localizedName,
                $limit
            );
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->localeFacade->getCurrentLocale();
    }
}
