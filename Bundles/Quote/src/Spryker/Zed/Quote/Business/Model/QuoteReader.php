<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     */
    public function __construct(QuoteRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @deprecated Use findQuoteByCustomerAndStore() instead.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $customerTransfer->requireCustomerReference();
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteByCustomer($customerTransfer->getCustomerReference());

        $quoteResponseTransfer = $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
        if ($quoteTransfer) {
            $quoteResponseTransfer->setCustomer($customerTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomerAndStore(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteResponseTransfer
    {
        $customerTransfer->requireCustomerReference();
        $storeTransfer->requireIdStore();
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteByCustomerReferenceAndIdStore(
                $customerTransfer->getCustomerReference(),
                $storeTransfer->getIdStore()
            );

        $quoteResponseTransfer = $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
        if ($quoteTransfer) {
            $quoteResponseTransfer->setCustomer($customerTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteById($idQuote): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(false);
        $quoteTransfer = $this->quoteRepository
            ->findQuoteById($idQuote);

        return $this->setQuoteResponseTransfer($quoteResponseTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function setQuoteResponseTransfer(QuoteResponseTransfer $quoteResponseTransfer, ?QuoteTransfer $quoteTransfer = null): QuoteResponseTransfer
    {
        if (!$quoteTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }
}
