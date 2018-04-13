<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;

class QuoteDeleter implements QuoteDeleterInterface
{
    use PermissionAwareTrait;
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var array|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteDeleteBeforePlugins;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteDeleteBeforePlugins
     */
    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        QuoteEntityManagerInterface $quoteEntityManager,
        array $quoteDeleteBeforePlugins
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->quoteRepository = $quoteRepository;
        $this->quoteDeleteBeforePlugins = $quoteDeleteBeforePlugins;
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
            return $this->executeDeleteTransaction($quoteTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateQuote(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getCustomer()) {
            return false;
        }
        $loadedQuoteTransfer = $this->quoteRepository->findQuoteById($quoteTransfer->getIdQuote());
        if (!$loadedQuoteTransfer) {
            return false;
        }
        $customerTransfer = $quoteTransfer->getCustomer();

        return strcmp($loadedQuoteTransfer->getCustomerReference(), $customerTransfer->getCustomerReference()) === 0
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('WriteSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeDeleteTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteTransfer = $this->executeDeleteBeforePlugins($quoteTransfer);
            $this->quoteEntityManager->deleteQuoteById($quoteTransfer->getIdQuote());
            $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
            $quoteResponseTransfer->setIsSuccessful(true);

            return $quoteResponseTransfer;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeDeleteBeforePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteDeleteBeforePlugins as $quoteWritePlugin) {
            $quoteTransfer = $quoteWritePlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
