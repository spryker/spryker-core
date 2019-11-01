<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityCartConnector\Business\AvailabilityCartConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityCartConnector\Communication\AvailabilityCartConnectorCommunicationFactory getFactory()
 */
class CheckAvailabilityPlugin extends AbstractPlugin implements CartPreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     *  - In case `ItemTransfer.amount` was defined, item availability check will be ignored.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFacade()->checkCartAvailability($cartChangeTransfer);
    }
}
