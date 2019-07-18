<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerOfferConnector\Communication\Plugin;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

/**
 * @method \Spryker\Zed\CustomerOfferConnector\Business\CustomerOfferConnectorFacadeInterface getFacade()
 */
class OfferCustomerHydratorPlugin extends AbstractPlugin implements OfferHydratorPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerTransfer = $this->getFacade()
            ->hydrateOfferWithCustomer($offerTransfer);

        return $offerTransfer;
    }
}
