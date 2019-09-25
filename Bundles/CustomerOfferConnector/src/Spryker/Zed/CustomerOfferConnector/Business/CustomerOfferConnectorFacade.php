<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerOfferConnector\Business;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerOfferConnector\Business\CustomerOfferConnectorBusinessFactory getFactory()
 */
class CustomerOfferConnectorFacade extends AbstractFacade implements CustomerOfferConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOfferWithCustomer(OfferTransfer $offerTransfer): OfferTransfer
    {
        return $this->getFactory()
            ->createOfferCustomerHydrator()
            ->hydrate($offerTransfer);
    }
}
