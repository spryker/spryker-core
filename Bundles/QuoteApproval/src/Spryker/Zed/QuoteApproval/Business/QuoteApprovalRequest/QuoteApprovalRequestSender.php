<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalRequestSender implements QuoteApprovalRequestSenderInterface
{
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_APPROVAL_REQUEST_SENT = 'quote_approval.approval_request.sent';

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestValidatorInterface
     */
    protected $quoteApprovalRequestValidator;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
     */
    public function __construct(
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToMessengerFacadeInterface $messengerFacade,
        QuoteApprovalToCompanyUserFacadeInterface $companyUserFacade,
        QuoteApprovalRequestValidatorInterface $quoteApprovalRequestValidator
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->messengerFacade = $messengerFacade;
        $this->companyUserFacade = $companyUserFacade;
        $this->quoteApprovalRequestValidator = $quoteApprovalRequestValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendQuoteApproveRequest(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): QuoteResponseTransfer
    {
        $quoteReposneTransfer = $this->createQuoteResponseTransfer($quoteApproveRequestTransfer);
        $approverTransfer = $this->getCompanyUserById($quoteApproveRequestTransfer->getIdApprover());
        $quoteTransfer = $quoteApproveRequestTransfer->getQuote();

        if (!$this->quoteApprovalRequestValidator->isApproveRequestValid($quoteApproveRequestTransfer)) {
            $quoteReposneTransfer->setIsSuccessful(false);
            $this->addPermissionFailedErrorMessage();

            return $quoteReposneTransfer;
        }

        $quoteTransfer = $this->updateQuoteShareDetails($quoteTransfer, $quoteApproveRequestTransfer->getIdApprover());
        $quoteTransfer = $this->cartFacade->lockQuote($quoteTransfer);
        $quoteTransfer = $this->updateQuoteApprovalRequests($quoteTransfer, $approverTransfer);

        $quoteReposneTransfer = $this->quoteFacade->updateQuote($quoteTransfer);

        $this->addSuccessMessage();

        return $quoteReposneTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function createQuoteResponseTransfer(
        QuoteApproveRequestTransfer $quoteApproveRequestTransfer
    ): QuoteResponseTransfer {
        $quoteReposneTransfer = new QuoteResponseTransfer();

        $quoteReposneTransfer->setIsSuccessful(true);
        $quoteReposneTransfer->setQuoteTransfer($quoteApproveRequestTransfer->getQuote());
        $quoteReposneTransfer->setCustomer($quoteApproveRequestTransfer->getCustomer());

        return $quoteReposneTransfer;
    }

    /**
     * @return void
     */
    protected function addPermissionFailedErrorMessage(): void
    {
        $this->messengerFacade->addErrorMessage(
            $this->createMessageTransfer(
                static::GLOSSARY_KEY_PERMISSION_FAILED
            )
        );
    }

    /**
     * @return void
     */
    protected function addSuccessMessage(): void
    {
        $this->messengerFacade->addSuccessMessage(
            $this->createMessageTransfer(
                static::GLOSSARY_KEY_APPROVAL_REQUEST_SENT
            )
        );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return bool
     */
    protected function isRequestSentByQuoteOwner(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): bool
    {
        $requestSender = $quoteApproveRequestTransfer->getCustomer();
        $quoteOwner = $quoteApproveRequestTransfer->getQuote()->getCustomer();

        return $requestSender->getCustomerReference() === $quoteOwner->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idApprover
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteShareDetails(QuoteTransfer $quoteTransfer, int $idApprover): QuoteTransfer
    {
        $shareDetailTransfer = new ShareDetailTransfer();

        $shareDetailTransfer->setIdCompanyUser($idApprover);
        $quoteTransfer->setShareDetails(new ArrayObject([$shareDetailTransfer]));

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $approver
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteApprovalRequests(
        QuoteTransfer $quoteTransfer,
        CompanyUserTransfer $approver
    ): QuoteTransfer {
        $quoteApprovalTransfer = new QuoteApprovalTransfer();

        $quoteApprovalTransfer->setStatus(QuoteApprovalConfig::STATUS_WAITING);
        $quoteApprovalTransfer->setApprover(
            $approver
        );

        $quoteTransfer->addApproval($quoteApprovalTransfer);

        return $quoteTransfer;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer
    {
        return $this->companyUserFacade->getCompanyUserById($idCompanyUser);
    }
}
