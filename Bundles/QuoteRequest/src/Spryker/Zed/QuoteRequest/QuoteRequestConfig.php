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
     * @return int
     */
    public function getInitialVersion(): int
    {
        return $this->getSharedConfig()->getInitialVersion();
    }

    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedQuoteRequestConfig::STATUS_DRAFT;
    }

    /**
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ITEMS,
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
    public function getEditableStatuses(): array
    {
        return $this->getSharedConfig()->getEditableStatuses();
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
    public function getUserEditableStatuses(): array
    {
        return $this->getSharedConfig()->getUserEditableStatuses();
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
