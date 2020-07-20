<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;

class ProductCartConnectorToProductBridge implements ProductCartConnectorToProductInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductBridge::getRawProductConcreteTransfersByConcreteSkus()} instead.
     *
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getRawProductConcreteBySku(string $productConcreteSku): ProductConcreteTransfer
    {
        return $this->productFacade->getRawProductConcreteBySku($productConcreteSku);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->productFacade->getLocalizedProductConcreteName($productConcreteTransfer, $localeTransfer);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->productFacade->hasProductConcrete($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->productFacade->hasProductAbstract($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->productFacade->isProductConcreteActive($productConcreteTransfer);
    }

    /**
     * @param array $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array
    {
        return $this->productFacade->getRawProductConcreteTransfersByConcreteSkus($productConcreteSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array
    {
        return $this->productFacade->getProductConcretesByCriteria($productCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getProductUrls(ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer): array
    {
        return $this->productFacade->getProductUrls($productUrlCriteriaFilterTransfer);
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        return $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);
    }
}
