<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

// TODO: this class is doing too much. Split it to QuoteReader, QuoteWriter, QuoteDeleter
class Quote implements QuoteInterface
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
     * @var \Spryker\Zed\Quote\Business\Model\QuotePluginExecutorInterface
     */
    protected $quotePluginExecutor;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\Quote\Business\Model\QuotePluginExecutorInterface $quotePluginExecutor
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        QuoteEntityManagerInterface $quoteEntityManager,
        QuotePluginExecutorInterface $quotePluginExecutor,
        QuoteToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->storeFacade = $storeFacade;
        $this->quotePluginExecutor = $quotePluginExecutor;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByCustomer(CustomerTransfer $customerTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteTransfer = $this->quoteRepository
            ->findQuoteByCustomer($customerTransfer->getCustomerReference());
        if (!$quoteTransfer) {
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }

        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $this->addStoreToQuote($quoteTransfer);
        $this->quotePluginExecutor->executeQuotePreSavePlugins($quoteTransfer);
        $quoteTransfer = $this->quoteEntityManager->saveQuote($quoteTransfer);
        $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function delete(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $this->quoteEntityManager->deleteQuoteById($quoteTransfer->getIdQuote());
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addStoreToQuote(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getStore()) {
            $quoteTransfer->setStore(
                $this->storeFacade->getCurrentStore()
            );
            return;
        }
        if ($quoteTransfer->getStore()->getIdStore()) {
            return;
        }
        $store = $this->storeFacade->getStoreByName($quoteTransfer->getStore()->getName());
        $quoteTransfer->setStore($store);
    }
}
