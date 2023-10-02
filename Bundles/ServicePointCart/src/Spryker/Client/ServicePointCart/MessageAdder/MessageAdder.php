<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointCart\MessageAdder;

use ArrayObject;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToGlossaryStorageClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToLocaleClientInterface;
use Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToMessengerClientInterface;

class MessageAdder implements MessageAdderInterface
{
    /**
     * @var \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToGlossaryStorageClientInterface
     */
    protected ServicePointCartToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @var \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToMessengerClientInterface
     */
    protected ServicePointCartToMessengerClientInterface $messengerClient;

    /**
     * @var \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToLocaleClientInterface
     */
    protected ServicePointCartToLocaleClientInterface $localeClient;

    /**
     * @param \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToMessengerClientInterface $messengerClient
     * @param \Spryker\Client\ServicePointCart\Dependency\Client\ServicePointCartToLocaleClientInterface $localeClient
     */
    public function __construct(
        ServicePointCartToGlossaryStorageClientInterface $glossaryStorageClient,
        ServicePointCartToMessengerClientInterface $messengerClient,
        ServicePointCartToLocaleClientInterface $localeClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->messengerClient = $messengerClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\QuoteErrorTransfer> $quoteErrorTransfers
     *
     * @return void
     */
    public function addQuoteResponseErrors(ArrayObject $quoteErrorTransfers): void
    {
        foreach ($quoteErrorTransfers as $quoteErrorTransfer) {
            $translatedErrorMessage = $this->glossaryStorageClient
                ->translate(
                    $quoteErrorTransfer->getMessageOrFail(),
                    $this->localeClient->getCurrentLocale(),
                    $quoteErrorTransfer->getParameters(),
                );

            $this->messengerClient->addErrorMessage($translatedErrorMessage);
        }
    }
}
