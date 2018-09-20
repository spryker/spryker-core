<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductQuantityStorage\ProductQuantityStorageClientInterface getClient()
 */
class QuickOrderProductAdditionalDataTransferQuantityRestrictionsExpanderPlugin extends AbstractPlugin implements QuickOrderProductAdditionalDataTransferExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands QuickOrderProductAdditionalDataTransfer with quantity restrictions data for given product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer
     */
    public function expandQuickOrderProductAdditionalDataTransfer(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): QuickOrderProductAdditionalDataTransfer
    {
        return $this->getClient()->expandQuickOrderProductAdditionalDataTransferWithQuantityRestrictions($quickOrderProductAdditionalDataTransfer);
    }
}
