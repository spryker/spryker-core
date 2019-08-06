<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;

class ProductBundleProductListConnectorToProductBundleFacadeBridge implements ProductBundleProductListConnectorToProductBundleFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     */
    public function __construct($productBundleFacade)
    {
        $this->productBundleFacade = $productBundleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function getProductBundleCollectionByCriteriaFilter(ProductBundleCriteriaFilterTransfer $productBundleCriteriaFilterTransfer): ProductBundleCollectionTransfer
    {
        return $this->productBundleFacade->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        return $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcrete);
    }
}
