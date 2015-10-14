<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Log;

use Generated\Shared\Payolution\OrderInterface;

interface TransactionStatusLogInterface
{

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderInterface $orderTransfer);

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderInterface $orderTransfer);

}
