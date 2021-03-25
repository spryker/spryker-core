<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Communication\Plugin\Product;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 */
class MerchantProductProductAbstractPostCreatePlugin extends AbstractPlugin implements ProductAbstractPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates a new merchant product abstract entity if ProductAbstractTransfer.idMerchant is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        if ($productAbstractTransfer->getIdMerchant()) {
            $this->getFacade()->create(
                (new MerchantProductTransfer())
                    ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract())
                    ->setIdMerchant($productAbstractTransfer->getIdMerchant())
            );
        }

        return $productAbstractTransfer;
    }
}
