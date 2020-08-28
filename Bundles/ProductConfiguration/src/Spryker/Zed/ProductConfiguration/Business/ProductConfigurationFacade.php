<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepository getRepository()
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationBusinessFactory getFactory()
 */
class ProductConfigurationFacade extends AbstractFacade implements ProductConfigurationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function getProductConfigurationCollection(
        ProductConfigurationFilterTransfer $productConfigurationFilterTransfer
    ): ProductConfigurationCollectionTransfer {
        return $this->getRepository()->getProductConfigurationCollection($productConfigurationFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer|void
     */
    public function expandProductConfigurationItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createProductConfigurationGroupKeyItemExpander()
            ->expandProductConfigurationItemsWithGroupKey($cartChangeTransfer);
    }
}
