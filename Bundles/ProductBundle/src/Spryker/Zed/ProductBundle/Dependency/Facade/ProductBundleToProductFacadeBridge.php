<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Exception\MissingFacadeMethodException;

class ProductBundleToProductFacadeBridge implements ProductBundleToProductFacadeInterface
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteCollection
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->productFacade->addProduct($productAbstractTransfer, $productConcreteCollection);
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
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->productFacade->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @throws \Spryker\Zed\ProductBundle\Exception\MissingFacadeMethodException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer): ProductConcreteCollectionTransfer
    {
        // This check is needed for BC reasons, because the method was added in spryker/product:6.35.0.
        if (method_exists($this->productFacade, 'getProductConcreteCollection')) {
            return $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);
        }

        throw new MissingFacadeMethodException();
    }
}
