<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\TaxApp\Plugins;

use Generated\Shared\Transfer\ItemTaxMetadataTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SaleTaxMetadataTransfer;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface;

class OrderTaxAppExpanderPlugin implements OrderTaxAppExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expand(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->setTaxMetadata(new SaleTaxMetadataTransfer());

        if ($orderTransfer->getItems()) {
            foreach ($orderTransfer->getItems() as $itemTransfer) {
                $itemTransfer->setTaxMetadata(new ItemTaxMetadataTransfer());
            }
        }

        return $orderTransfer;
    }
}
