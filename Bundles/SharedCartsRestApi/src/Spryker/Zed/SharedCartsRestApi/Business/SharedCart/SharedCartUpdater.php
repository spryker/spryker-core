<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\SharedCart;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareCartResponseTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;
use Spryker\Shared\SharedCartsRestApi\SharedCartsRestApiConfig as SharedSharedCartsRestApiConfig;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;

class SharedCartUpdater implements SharedCartUpdaterInterface
{
    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade
     */
    public function __construct(SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade)
    {
        $this->sharedCartFacade = $sharedCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function update(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        $shareCartResponseTransfer = (new ShareCartResponseTransfer())->setIsSuccessful(false);

        $shareCartRequestTransfer->requireShareDetails();
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareCartRequestTransfer->getShareDetails()->offsetGet(0);
        $shareDetailTransfer->requireUuid();

        $quoteCompanyUserTransfer = $this->sharedCartFacade->findQuoteCompanyUserByUuid(
            (new QuoteCompanyUserTransfer())->setUuid($shareDetailTransfer->getUuid())
        );

        if (!$quoteCompanyUserTransfer) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND);
        }

        $quotePermissionGroupResponseTransfer = $this->sharedCartFacade->findQuotePermissionGroupById(
            (new QuotePermissionGroupTransfer())
                ->setIdQuotePermissionGroup($shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup())
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_QUOTE_PERMISSION_GROUP_NOT_FOUND);
        }

        $quoteTransfer = $quoteCompanyUserTransfer->getQuote();
        if (!$this->canManageQuoteSharing($quoteTransfer, $shareCartRequestTransfer)) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN);
        }

        $shareDetailTransfer->setIdQuoteCompanyUser($quoteCompanyUserTransfer->getIdQuoteCompanyUser());

        $shareCartResponseTransfer = $this->sharedCartFacade->updateQuoteCompanyUserPermissionGroup(
            (new ShareCartRequestTransfer())->addShareDetail($shareDetailTransfer)
        );

        if (!$shareCartResponseTransfer->getIsSuccessful()) {
            $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_TO_SAVE_SHARED_CART);
        }

        return $shareCartResponseTransfer->setIsSuccessful(true)
            ->setShareDetails($shareCartResponseTransfer->getShareDetails());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return bool
     */
    protected function canManageQuoteSharing(QuoteTransfer $quoteTransfer, ShareCartRequestTransfer $shareCartRequestTransfer): bool
    {
        return $quoteTransfer->getCustomerReference() === $shareCartRequestTransfer->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer
     */
    protected function createShareDetailCriteriaFilterTransfer(
        QuoteTransfer $quoteTransfer,
        QuoteCompanyUserTransfer $quoteCompanyUserTransfer
    ): ShareDetailCriteriaFilterTransfer {
        return (new ShareDetailCriteriaFilterTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setIdCompanyUser($quoteCompanyUserTransfer->getFkCompanyUser());
    }
}
