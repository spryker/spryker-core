<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer;
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
    protected $zedStub;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface $zedStub
     */
    public function __construct(
        ConfigurableBundleCartNoteToQuoteClientInterface $quoteClient,
        ConfigurableBundleCartNoteZedStubInterface $zedStub
    ) {
        $this->quoteClient = $quoteClient;
        $this->zedStub = $zedStub;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param string $cartNote
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $cartNote, string $configurableBundleGroupKey): QuoteResponseTransfer
    {
        $configurableBundleCartNoteRequestTransfer = $this->createConfigurableBundleCartNoteRequest($cartNote, $configurableBundleGroupKey);
        $quoteResponseTransfer = $this->zedStub->setCartNoteToConfigurableBundle($configurableBundleCartNoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }

    /**
     * @param string $cartNote
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleCartNoteRequestTransfer
     */
    protected function createConfigurableBundleCartNoteRequest(
        string $cartNote,
        string $configurableBundleGroupKey
    ): ConfigurableBundleCartNoteRequestTransfer {
        $quoteTransfer = $this->quoteClient->getQuote();
        $customerTransfer = $quoteTransfer->getCustomer();

        return (new ConfigurableBundleCartNoteRequestTransfer())
            ->setCustomer($customerTransfer)
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setConfigurableBundleGroupKey($configurableBundleGroupKey)
            ->setCartNote($cartNote);
    }
}
