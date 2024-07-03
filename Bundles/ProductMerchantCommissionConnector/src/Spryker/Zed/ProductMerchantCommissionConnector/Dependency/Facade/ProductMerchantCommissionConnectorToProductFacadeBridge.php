<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductMerchantCommissionConnectorToProductFacadeBridge implements ProductMerchantCommissionConnectorToProductFacadeInterface
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
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        return $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array<string, string>
     */
    public function getCombinedConcreteAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        ?LocaleTransfer $localeTransfer = null
    ): array {
        return $this->productFacade->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function getProductAttributeKeyCollection(
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): ProductAttributeKeyCollectionTransfer {
        return $this->productFacade->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);
    }
}
