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
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Shared\SharedCartsRestApi\SharedCartsRestApiConfig as SharedSharedCartsRestApiConfig;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManagerInterface;

class SharedCartCreator implements SharedCartCreatorInterface
{
    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManagerInterface
     */
    protected $sharedCartsRestApiEntityManager;

    /**
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade
     * @param \Spryker\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiEntityManagerInterface $sharedCartsRestApiEntityManager
     */
    public function __construct(
        SharedCartsRestApiToQuoteFacadeInterface $quoteFacade,
        SharedCartsRestApiToSharedCartFacadeInterface $sharedCartFacade,
        SharedCartsRestApiEntityManagerInterface $sharedCartsRestApiEntityManager
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->sharedCartFacade = $sharedCartFacade;
        $this->sharedCartsRestApiEntityManager = $sharedCartsRestApiEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShareCartResponseTransfer
     */
    public function create(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
    {
        $shareCartRequestTransfer->requireQuoteUuid()
            ->requireShareDetails();
        $shareCartResponseTransfer = (new ShareCartResponseTransfer())->setIsSuccessful(false);

        $quoteResponseTransfer = $this->quoteFacade->findQuoteByUuid(
            (new QuoteTransfer())->setUuid($shareCartRequestTransfer->getQuoteUuid())
        );
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_QUOTE_NOT_FOUND);
        }

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        if (!$this->canManageQuoteSharing($quoteTransfer, $shareCartRequestTransfer)) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN);
        }

        $quoteCompanyUserTransfer = $this->createQuoteCompanyUserTransfer(
            $quoteTransfer->getIdQuote(),
            $shareCartRequestTransfer->getShareDetails()->offsetGet(0)
        );

        $shareDetailCollection = $this->sharedCartFacade->findShareDetailsCollectionByShareDetailCriteria(
            $this->createShareDetailCriteriaFilterTransfer($quoteTransfer, $quoteCompanyUserTransfer)
        );
        if ($shareDetailCollection->getShareDetails()->count() === 0) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_FAILED_TO_SHARE_CART);
        }

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
     * @param int $idQuote
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer
     */
    protected function createQuoteCompanyUserTransfer(int $idQuote, ShareDetailTransfer $shareDetailTransfer): QuoteCompanyUserTransfer
    {
        $quoteCompanyUserTransfer = (new QuoteCompanyUserTransfer())
            ->setFkQuote($idQuote)
            ->setFkCompanyUser($shareDetailTransfer->getIdCompanyUser())
            ->setFkQuotePermissionGroup($shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());

        return $this->sharedCartsRestApiEntityManager->saveQuoteCompanyUser($quoteCompanyUserTransfer);
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
