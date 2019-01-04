<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment;

use \ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Shipment\ShipmentFactory getFactory()
 */
class ShipmentClient extends AbstractClient implements ShipmentClientInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->getAvailableMethods($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function getShipmentGroups(ItemCollectionTransfer $itemCollectionTransfer): ArrayObject
    {
        return $this->getFactory()->createZedStub()->getShipmentGroups($itemCollectionTransfer);
    }
}
