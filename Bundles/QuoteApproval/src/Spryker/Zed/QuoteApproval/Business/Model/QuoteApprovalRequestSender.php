<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalRequestSender implements QuoteApprovalRequestSenderInterface
{
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

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
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToCompanyRoleFacadeInterface $companyRoleFacade,
        QuoteApprovalToMessengerFacadeInterface $messengerFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->companyRoleFacade = $companyRoleFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendQuoteApproveRequest(QuoteApproveRequestTransfer $quoteApproveRequestTransfer): QuoteResponseTransfer
    {
        $quoteReposneTransfer = $this->createQuoteResponseTransfer($quoteApproveRequestTransfer);

        if (!$this->isRequestSentByQuoteOwner($quoteApproveRequestTransfer)
            || !$this->isApproverHasPermission($quoteApproveRequestTransfer->getIdApprover())) {
            $quoteReposneTransfer->setIsSuccessful(false);

            $this->addPermissionFailedErrorMessage();

            return $quoteReposneTransfer;
        }

        $quoteTransfer = $quoteApproveRequestTransfer->getQuote();

        $quoteTransfer = $this->updateShareDetails($quoteTransfer, $quoteApproveRequestTransfer->getIdApprover());
        $quoteTransfer = $this->cartFacade->lockQuote($quoteTransfer);
        $quoteTransfer = $this->updateQuoteApprovalRequests($quoteTransfer, $quoteApproveRequestTransfer->getIdApprover());

        $this->quoteFacade->updateQuote($quoteTransfer);

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
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_PERMISSION_FAILED);

        $this->messengerFacade->addErrorMessage($messageTransfer);
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
     * @param int $idApprover
     *
     * @return bool
     */
    protected function isApproverHasPermission(int $idApprover): bool
    {
        $permissionCollectionTransfer = $this->companyRoleFacade->findPermissionsByIdCompanyUser($idApprover);

        foreach ($permissionCollectionTransfer->getPermissions() as $permission) {
            if ($permission->getKey() === ApproveQuotePermissionPlugin::KEY) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idApprover
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateShareDetails(QuoteTransfer $quoteTransfer, int $idApprover): QuoteTransfer
    {
        $shareDetailTransfer = new ShareDetailTransfer();

        $shareDetailTransfer->setIdCompanyUser($idApprover);
        $quoteTransfer->setShareDetails(new ArrayObject([$shareDetailTransfer]));

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idApprover
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteApprovalRequests(QuoteTransfer $quoteTransfer, int $idApprover): QuoteTransfer
    {
        $quoteApprovalTransfer = new QuoteApprovalTransfer();

        $quoteApprovalTransfer->setStatus(QuoteApprovalConfig::STATUS_WAITING);
        $quoteApprovalTransfer->setApprover(
            (new CompanyUserTransfer())->setIdCompanyUser($idApprover)
        );

        $quoteTransfer->addApproval($quoteApprovalTransfer);

        return $quoteTransfer;
    }
}
