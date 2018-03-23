<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class MultiCartClient extends AbstractClient implements MultiCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getDefaultCart(): QuoteTransfer
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->getQuote();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->getFactory()
            ->createMultiCartZedStub()
            ->setDefaultQuote($quoteActivationRequestTransfer);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $quoteResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->getFactory()
            ->createMultiCartStorage()
            ->getQuoteCollection();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $quoteName
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteByName($quoteName): ?QuoteTransfer
    {
        return $this->getFactory()
            ->createMultiCartStorage()
            ->findQuoteByName($quoteName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function isMultiCartAllowed()
    {
        $storageStrategy = $this->getFactory()
            ->getQuoteClient()
            ->getStorageStrategy();

        return $storageStrategy === QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * Specification:
     * - Get suffix for duplicated quote name
     *
     * @api
     *
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix()
    {
        return $this->getFactory()
            ->getMultiCartConfig()
            ->getDuplicatedQuoteNameSuffix();
    }
}
