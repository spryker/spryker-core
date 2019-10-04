<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Permission\ContextProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteApproval\QuoteApprovalConfig;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig as SharedQuoteApprovalConfig;

class PermissionContextProvider implements PermissionContextProviderInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\QuoteApprovalConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\QuoteApproval\QuoteApprovalConfig $config
     */
    public function __construct(QuoteApprovalConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function provideContext(QuoteTransfer $quoteTransfer): array
    {
        return [
            SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT => $this->getQuoteSum($quoteTransfer),
            SharedQuoteApprovalConfig::PERMISSION_CONTEXT_STORE_NAME => $quoteTransfer->getStore()->getName(),
            SharedQuoteApprovalConfig::PERMISSION_CONTEXT_CURRENCY_CODE => $quoteTransfer->getCurrency()->getCode(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getQuoteSum(QuoteTransfer $quoteTransfer): int
    {
        if ($quoteTransfer->getTotals() === null) {
            return 0;
        }

        if ($this->config->isPermissionCalculationIncludeShipment()) {
            return $quoteTransfer->getTotals()->getGrandTotal();
        }

        return $quoteTransfer->getTotals()->getGrandTotal() - $this->getShipmentPriceForQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getShipmentPriceForQuote(QuoteTransfer $quoteTransfer): int
    {
        if (!$quoteTransfer->getShipment()) {
            return 0;
        }

        $shipmentMethodTransfer = $quoteTransfer->getShipment()
            ->getMethod();

        if (!$shipmentMethodTransfer) {
            return 0;
        }

        return $shipmentMethodTransfer->getStoreCurrencyPrice();
    }
}
