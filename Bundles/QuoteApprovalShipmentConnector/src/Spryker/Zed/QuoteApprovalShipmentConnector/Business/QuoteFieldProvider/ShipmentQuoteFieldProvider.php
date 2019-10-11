<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Business\QuoteFieldProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface;

class ShipmentQuoteFieldProvider implements ShipmentQuoteFieldProviderInterface
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @uses \Spryker\Shared\QuoteApproval\QuoteApprovalConfig::STATUS_WAITING
     */
    protected const QUOTE_APPROVAL_STATUS_WAITING = 'waiting';

    /**
     * @uses \Spryker\Shared\QuoteApproval\QuoteApprovalConfig::STATUS_APPROVED
     */
    protected const QUOTE_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface
     */
    protected $quoteApprovalFacade;

    /**
     * @param \Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade\QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface $quoteApprovalFacade
     */
    public function __construct(QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface $quoteApprovalFacade)
    {
        $this->quoteApprovalFacade = $quoteApprovalFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        if (!$this->isQuoteApprovalRequestWaitingOrApproved($quoteTransfer)) {
            return [];
        }

        if ($this->isQuoteLevelShipment($quoteTransfer)) {
            return $this->getQuoteLevelShipmentQuoteFieldsAllowedForSaving();
        }

        return [];
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteLevelShipment(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getShipment()) {
            return false;
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getType() === static::SHIPMENT_EXPENSE_TYPE) {
                return $quoteTransfer->getShipment()->getShipmentSelection() !== null;
            }
        }

        return false;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @return array
     */
    protected function getQuoteLevelShipmentQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::SHIPMENT,
            QuoteTransfer::SHIPPING_ADDRESS,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteApprovalRequestWaitingOrApproved(QuoteTransfer $quoteTransfer): bool
    {
        return in_array($this->quoteApprovalFacade->calculateQuoteStatus($quoteTransfer), [
            static::QUOTE_APPROVAL_STATUS_WAITING,
            static::QUOTE_APPROVAL_STATUS_APPROVED,
        ]);
    }
}
