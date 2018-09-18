<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderProductAdditionalDataTransferExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageClientInterface getClient()
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageFactory getFactory()
 */
class QuickOrderProductAdditionalDataTransferMeasurementUnitExpanderPlugin extends AbstractPlugin implements QuickOrderProductAdditionalDataTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductAdditionalDataTransfer
     */
    public function expandQuickOrderProductAdditionalDataTransfer(QuickOrderProductAdditionalDataTransfer $quickOrderProductAdditionalDataTransfer): QuickOrderProductAdditionalDataTransfer
    {
        $productMeasurementUnitTransfer = $this->getClient()->findProductMeasurementBaseUnitByIdProduct(
            $quickOrderProductAdditionalDataTransfer->getIdProductConcrete()
        );

        if ($productMeasurementUnitTransfer === null) {
            return $quickOrderProductAdditionalDataTransfer;
        }

        $translatedName = $this->getFactory()
            ->getGlossaryStorageClient()
            ->translate(
                $productMeasurementUnitTransfer->getName(),
                $this->getFactory()->getLocaleClient()->getCurrentLocale()
            );

        $productMeasurementUnitTransfer->setName($translatedName);
        $quickOrderProductAdditionalDataTransfer->setBaseMeasurementUnit($productMeasurementUnitTransfer);

        return $quickOrderProductAdditionalDataTransfer;
    }
}
