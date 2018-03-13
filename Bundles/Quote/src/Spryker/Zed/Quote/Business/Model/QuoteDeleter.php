<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

class QuoteDeleter implements QuoteDeleterInterface
{
    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     */
    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        QuoteEntityManagerInterface $quoteEntityManager
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function delete(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        if ($this->validateQuote($quoteTransfer)) {
            $this->quoteEntityManager->deleteQuoteById($quoteTransfer->getIdQuote());
            $quoteResponseTransfer->setIsSuccessful(true);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateQuote(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getCustomer()) {
            return false;
        }
        $loadedQuoteTransfer = $this->quoteRepository->findQuoteById($quoteTransfer->getIdQuote());
        if (!$loadedQuoteTransfer) {
            return false;
        }

        return strcmp($loadedQuoteTransfer->getCustomerReference(), $quoteTransfer->getCustomer()->getCustomerReference()) === 0;
    }
}
