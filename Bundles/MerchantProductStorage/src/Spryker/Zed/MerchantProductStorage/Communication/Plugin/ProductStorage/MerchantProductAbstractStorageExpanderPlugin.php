<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Communication\Plugin\ProductStorage;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Communication\MerchantProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductStorage\Business\MerchantProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 */
class MerchantProductAbstractStorageExpanderPlugin extends AbstractPlugin implements ProductAbstractStorageExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the provided ProductAbstractStorage transfer object.
     * - Finds merchant product relation for ProductAbstractStorage.idProductAbstract.
     * - Sets ProductAbstractStorage.merchantReference from found merchant product relation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expand(ProductAbstractStorageTransfer $productAbstractStorageTransfer): ProductAbstractStorageTransfer
    {
        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->setIdProductAbstract($productAbstractStorageTransfer->getIdProductAbstract());

        $merchantTransfer = $this->getFactory()
            ->getMerchantProductFacade()
            ->findMerchant($merchantProductCriteriaTransfer);

        if (!$merchantTransfer) {
            return $productAbstractStorageTransfer;
        }

        return $productAbstractStorageTransfer->setMerchantReference($merchantTransfer->getMerchantReference());
    }
}
