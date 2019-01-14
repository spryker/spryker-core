<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Shared\QuoteRequest\QuoteRequestConstants;
use Spryker\Shared\SequenceNumber\SequenceNumberConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class QuoteRequestConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedQuoteRequestConfig::STATUS_DRAFT;
    }

    /**
     * @return int
     */
    public function getInitialVersion(): int
    {
        return SharedQuoteRequestConfig::DEFAULT_VERSION;
    }

    /**
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ITEMS,
            QuoteTransfer::TOTALS,
            QuoteTransfer::CURRENCY,
            QuoteTransfer::PRICE_MODE,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getQuoteRequestReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = (new SequenceNumberSettingsTransfer())
            ->setName(QuoteRequestConstants::NAME_QUOTE_REQUEST_REFERENCE);

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = 'RfQ'; // TODO: ask Karoly about it;
        $sequenceNumberPrefixParts[] = $this->get(SequenceNumberConstants::ENVIRONMENT_PREFIX);

        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts) . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator(): string
    {
        return '-';
    }
}
