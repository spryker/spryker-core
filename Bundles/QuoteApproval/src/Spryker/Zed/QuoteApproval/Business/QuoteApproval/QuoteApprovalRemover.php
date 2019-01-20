<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer;
use Generated\Shared\Transfer\QuoteApprovalResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;

class QuoteApprovalRemover implements QuoteApprovalRemoverInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_APPROVAL_REMOVED = 'quote_approval.removed';

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface
     */
    protected $quoteApprovalEntityManager;

    /**
     * @var \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    protected $quoteApprovalRepository;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager
     * @param \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface $quoteApprovalRepository
     */
    public function __construct(
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToSharedCartFacadeInterface $sharedCartFacade,
        QuoteApprovalEntityManagerInterface $quoteApprovalEntityManager,
        QuoteApprovalRepositoryInterface $quoteApprovalRepository
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->sharedCartFacade = $sharedCartFacade;
        $this->quoteApprovalEntityManager = $quoteApprovalEntityManager;
        $this->quoteApprovalRepository = $quoteApprovalRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    public function removeQuoteApproval(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteApprovalRemoveRequestTransfer) {
            return $this->executeRemoveQuoteApprovalTransaction($quoteApprovalRemoveRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteApprovalResponseTransfer
     */
    protected function executeRemoveQuoteApprovalTransaction(QuoteApprovalRemoveRequestTransfer $quoteApprovalRemoveRequestTransfer): QuoteApprovalResponseTransfer
    {
        $quoteApprovalResponseTransfer = new QuoteApprovalResponseTransfer();

        $quoteTransfer = $this->findQuoteByIdQuoteApproval($quoteApprovalRemoveRequestTransfer->getIdQuoteApproval());

        if (!$quoteTransfer
            || $quoteTransfer->getCustomerReference() !== $quoteApprovalRemoveRequestTransfer->getCustomerReference()
        ) {
            $quoteApprovalResponseTransfer->setIsSuccessful(false)
                ->setMessage($this->createMessageTransfer(static::GLOSSARY_KEY_PERMISSION_FAILED));

            return $quoteApprovalResponseTransfer;
        }

        $quoteTransfer = $this->cartFacade->unlockQuote($quoteTransfer);
        $this->quoteFacade->updateQuote($quoteTransfer);

        $this->sharedCartFacade->deleteShareForQuote($quoteTransfer);
        $this->quoteApprovalEntityManager->deleteQuoteApprovalById(
            $quoteApprovalRemoveRequestTransfer->getIdQuoteApproval()
        );

        $quoteApprovalResponseTransfer->setIsSuccessful(false)
            ->setMessage($this->createMessageTransfer(static::GLOSSARY_KEY_APPROVAL_REMOVED));

        $quoteApprovalResponseTransfer->setIsSuccessful(true);

        return $quoteApprovalResponseTransfer;
    }

    /**
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteByIdQuoteApproval(int $idQuoteApproval): ?QuoteTransfer
    {
        $idQuote = $this->quoteApprovalRepository->findIdQuoteByIdQuoteApproval($idQuoteApproval);

        if ($idQuote === null) {
            return null;
        }

        $quoteTransfer = $this->quoteFacade->findQuoteById($idQuote)->getQuoteTransfer();

        $quoteTransfer->setCustomer(
            (new CustomerTransfer())->setCustomerReference($quoteTransfer->getCustomerReference())
        );

        return $quoteTransfer;
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $parameters = []): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($parameters);

        return $messageTransfer;
    }
}
