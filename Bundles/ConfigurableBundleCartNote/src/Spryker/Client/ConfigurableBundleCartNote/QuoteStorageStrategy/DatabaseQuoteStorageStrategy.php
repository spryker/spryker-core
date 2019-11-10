<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface;
use Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    protected const STORAGE_STRATEGY = 'database';

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface
     */
    protected $configurableBundleCartNoteZedStub;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface $configurableBundleCartNoteZedStub
     */
    public function __construct(
        ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient,
        ConfigurableBundleCartNoteZedStubInterface $configurableBundleCartNoteZedStub
    ) {
        $this->quoteClient = $quoteClient;
        $this->configurableBundleCartNoteZedStub = $configurableBundleCartNoteZedStub;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->persistCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function persistCartNoteToConfigurableBundle(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        $quoteTransfer = $this->quoteClient->getQuote();
        $configuredBundleCartNoteRequestTransfer
            ->setCustomer($quoteTransfer->getCustomer())
            ->setIdQuote($quoteTransfer->getIdQuote());

        return $this->configurableBundleCartNoteZedStub->setCartNoteToConfigurableBundle($configuredBundleCartNoteRequestTransfer);
    }
}
