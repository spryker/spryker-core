<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Checkout\Service\Model;

use SprykerFeature\Shared\Library\Communication\Response;
use Generated\Shared\Transfer\OrderTransfer;

interface CheckoutManagerInterface
{
    /**
     * @param OrderTransfer $order
     * @return Response
     */
    public function saveOrder(OrderTransfer $order);

    /**
     * @param OrderTransfer $order
     * @return OrderTransfer
     */
    public function clearReferences(OrderTransfer $order);
}
