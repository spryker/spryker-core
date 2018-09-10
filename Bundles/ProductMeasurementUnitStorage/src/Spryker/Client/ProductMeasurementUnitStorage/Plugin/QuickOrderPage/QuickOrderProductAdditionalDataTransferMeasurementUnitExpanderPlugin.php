<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageClientInterface getClient()
 */
class QuickOrderProductAdditionalDataTransferMeasurementUnitExpanderPlugin extends AbstractPlugin implements QuickOrderProductAdditionalDataTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return void
     */
    public function expand(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): void
    {
        $productMeasurementUnitTransfer = $this->getClient()->findProductMeasurementBaseUnitByIdProduct(
            $quickOrderProductAdditionalDataTransfer->getIdProductConcrete()
        );

        if ($productMeasurementUnitTransfer === null) {
            return;
        }

        $quickOrderProductAdditionalDataTransfer->setBaseMeasurementUnit($productMeasurementUnitTransfer);
    }
}
