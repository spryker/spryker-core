<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductBundleCollectionTransfer;

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
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    public function findProductBundleCollectionByAssignedIdProductConcrete(int $idProductConcrete): ProductBundleCollectionTransfer
    {
        return $this->productBundleFacade->findProductBundleCollectionByAssignedIdProductConcrete($idProductConcrete);
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
