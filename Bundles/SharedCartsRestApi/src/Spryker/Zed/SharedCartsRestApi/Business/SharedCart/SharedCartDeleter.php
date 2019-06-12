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
use Spryker\Shared\SharedCartsRestApi\SharedCartsRestApiConfig as SharedSharedCartsRestApiConfig;
use Spryker\Zed\SharedCartsRestApi\Dependency\Facade\SharedCartsRestApiToSharedCartFacadeInterface;

class SharedCartDeleter implements SharedCartDeleterInterface
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
    public function delete(ShareCartRequestTransfer $shareCartRequestTransfer): ShareCartResponseTransfer
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

        $quoteTransfer = $quoteCompanyUserTransfer->getQuote();
        if (!$this->canManageQuoteSharing($quoteTransfer, $shareCartRequestTransfer)) {
            return $shareCartResponseTransfer->setErrorIdentifier(SharedSharedCartsRestApiConfig::ERROR_IDENTIFIER_ACTION_FORBIDDEN);
        }

        $shareDetailTransfer->setIdQuoteCompanyUser($quoteCompanyUserTransfer->getIdQuoteCompanyUser());
        $this->sharedCartFacade->deleteQuoteCompanyUser(
            (new ShareCartRequestTransfer())->addShareDetail($shareDetailTransfer)
        );

        return $shareCartResponseTransfer->setIsSuccessful(true);
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
}
