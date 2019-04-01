<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\QuoteRequest\QuoteRequestConfig getSharedConfig()
 */
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
        return SharedQuoteRequestConfig::INITIAL_VERSION_NUMBER;
    }

    /**
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::QUOTE_REQUEST_VERSION_REFERENCE,
            QuoteTransfer::QUOTE_REQUEST_REFERENCE,
            QuoteTransfer::IS_LOCKED,
        ];
    }

    /**
     * @return string[]
     */
    public function getCancelableStatuses(): array
    {
        return $this->getSharedConfig()->getCancelableStatuses();
    }

    /**
     * @return string[]
     */
    public function getRevisableStatuses(): array
    {
        return $this->getSharedConfig()->getRevisableStatuses();
    }

    /**
     * @return string[]
     */
    public function getUserCancelableStatuses(): array
    {
        return $this->getSharedConfig()->getUserCancelableStatuses();
    }

    /**
     * @return string[]
     */
    public function getUserRevisableStatuses(): array
    {
        return $this->getSharedConfig()->getUserRevisableStatuses();
    }

    /**
     * @return string
     */
    public function getQuoteRequestReferenceFormat(): string
    {
        return '%s-%s';
    }
}
