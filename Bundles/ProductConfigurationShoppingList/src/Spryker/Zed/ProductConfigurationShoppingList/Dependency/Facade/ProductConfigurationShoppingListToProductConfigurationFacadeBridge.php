<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;

class ProductConfigurationShoppingListToProductConfigurationFacadeBridge implements ProductConfigurationShoppingListToProductConfigurationFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface
     */
    protected $productConfigurationFacade;

    /**
     * @param \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface $productConfigurationFacade
     */
    public function __construct($productConfigurationFacade)
    {
        $this->productConfigurationFacade = $productConfigurationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationCriteriaTransfer $productConfigurationCriteriaTransfer
    ): ProductConfigurationCollectionTransfer {
        return $this->productConfigurationFacade->getProductConfigurationCollection($productConfigurationCriteriaTransfer);
    }
}
