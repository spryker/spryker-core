<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantShipment\Business\MerchantShipmentBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentRepositoryInterface getRepository()
 */
class MerchantShipmentFacade extends AbstractFacade implements MerchantShipmentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipment(MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer): ?ShipmentTransfer
    {
        return $this->getFactory()->createMerchantShipmentReader()->findShipment($merchantShipmentCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeShipmentWithMerchantReference(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createShipmentExpander()
            ->expandCartChangeShipmentWithMerchantReference($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteShipmentWithMerchantReference(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createShipmentExpander()
            ->expandQuoteShipmentWithMerchantReference($quoteTransfer);
    }
}
