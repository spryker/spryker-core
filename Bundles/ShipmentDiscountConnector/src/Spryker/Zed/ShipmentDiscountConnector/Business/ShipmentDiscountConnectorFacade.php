<?php

namespace Spryker\Zed\ShipmentDiscountConnector\Business;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;


/**
 * @method \Spryker\Zed\ShipmentDiscountConnector\Business\ShipmentDiscountConnectorBusinessFactory getFactory()
 */
class ShipmentDiscountConnectorFacade extends AbstractFacade implements ShipmentDiscountConnectorFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getCarrierList()
    {
        return $this->getFactory()
            ->createShipmentDiscountConnectorReader()
            ->getCarrierList();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectDiscountByShipmentCarrier(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createCarrierDiscountCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCarrierSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createCarrierDiscountRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

}