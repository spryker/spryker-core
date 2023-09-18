<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\TaxApp\Plugins;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTaxMetadataTransfer;
use Generated\Shared\Transfer\SaleTaxMetadataTransfer;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface;

class CalculableObjectTaxAppExpanderPlugin implements CalculableObjectTaxAppExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expand(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer->setTaxMetadata(new SaleTaxMetadataTransfer());

        if ($calculableObjectTransfer->getItems()) {
            foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setTaxMetadata(new ItemTaxMetadataTransfer());
            }
        }

        return $calculableObjectTransfer;
    }
}
