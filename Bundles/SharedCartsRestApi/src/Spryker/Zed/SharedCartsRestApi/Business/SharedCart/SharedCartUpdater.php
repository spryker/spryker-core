<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business\SharedCart;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
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
        $shareCartRequestTransfer->requireShareDetails();
        /** @var \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer */
        $shareDetailTransfer = $shareCartRequestTransfer->getShareDetails()->offsetGet(0);

        $quoteCompanyUserTransfer = $this->sharedCartFacade->findQuoteCompanyUserByUuid(
            (new QuoteCompanyUserTransfer())->setUuid($shareDetailTransfer->getUuid())
        );

        $shareCartResponseTransfer = (new ShareCartResponseTransfer())->setIsSuccessful(false);
        if (!$quoteCompanyUserTransfer) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_SHARED_CART_NOT_FOUND);
        }

        $quoteTransfer = $quoteCompanyUserTransfer->getQuote();
        if (!$this->canManageQuoteSharing($quoteTransfer, $shareCartRequestTransfer)) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN);
        }

        $shareDetailTransfer->setIdQuoteCompanyUser($quoteCompanyUserTransfer->getIdQuoteCompanyUser());
        $this->sharedCartFacade->updateQuoteCompanyUserPermissionGroup(
            (new ShareCartRequestTransfer())->addShareDetail($shareDetailTransfer)
        );

        $shareDetailCollection = $this->sharedCartFacade->findShareDetailsCollectionByShareDetailCriteria(
            $this->createShareDetailCriteriaFilterTransfer($quoteTransfer, $quoteCompanyUserTransfer)
        );

        return $shareCartResponseTransfer->setShareDetails($shareDetailCollection->getShareDetails())
            ->setIsSuccessful(true);
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
