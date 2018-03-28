<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

class QuoteWriter implements QuoteWriterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutorInterface
     */
    protected $quoteWriterPluginExecutor;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutorInterface $quoteWriterPluginExecutor
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteEntityManagerInterface $quoteEntityManager,
        QuoteRepositoryInterface $quoteRepository,
        QuoteWriterPluginExecutorInterface $quoteWriterPluginExecutor,
        QuoteToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->storeFacade = $storeFacade;
        $this->quoteRepository = $quoteRepository;
        $this->quoteWriterPluginExecutor = $quoteWriterPluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function save(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($quoteTransfer->getIdQuote()) {
            return $this->update($quoteTransfer);
        }

        return $this->create($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function create(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if ($quoteTransfer->getIdQuote()) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }
        $this->addStoreToQuote($quoteTransfer);

        return $this->executeCreateTransaction($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function update(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteByIdTransfer = $this->quoteRepository->findQuoteById($quoteTransfer->getIdQuote());
        if (!$quoteByIdTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);
            return $quoteResponseTransfer;
        }
        $this->addStoreToQuote($quoteTransfer);

        return $this->executeUpdateTransaction($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeCreateTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);
            $quoteTransfer = $this->quoteWriterPluginExecutor->executeCreateBeforePlugins($quoteTransfer);
            $quoteTransfer = $this->quoteEntityManager->saveQuote($quoteTransfer);
            $quoteTransfer = $this->quoteWriterPluginExecutor->executeCreateAfterPlugins($quoteTransfer);
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(true);

            return $quoteResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeUpdateTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);
            $quoteTransfer = $this->quoteWriterPluginExecutor->executeUpdateBeforePlugins($quoteTransfer);
            $quoteTransfer = $this->quoteEntityManager->saveQuote($quoteTransfer);
            $quoteTransfer = $this->quoteWriterPluginExecutor->executeUpdateAfterPlugins($quoteTransfer);
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setIsSuccessful(true);

            return $quoteResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function addStoreToQuote(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getStore()) {
            $quoteTransfer->setStore($this->storeFacade->getCurrentStore());

            return;
        }
        if ($quoteTransfer->getStore()->getIdStore()) {
            return;
        }
        $store = $this->storeFacade->getStoreByName($quoteTransfer->getStore()->getName());
        $quoteTransfer->setStore($store);
    }
}
