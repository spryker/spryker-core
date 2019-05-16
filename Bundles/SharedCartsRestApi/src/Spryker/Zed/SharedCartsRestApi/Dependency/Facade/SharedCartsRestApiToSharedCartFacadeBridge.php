<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\QuoteCompanyUserTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer;

class SharedCartsRestApiToSharedCartFacadeBridge implements SharedCartsRestApiToSharedCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    protected $sharedCartFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface $sharedCartFacade
     */
    public function __construct($sharedCartFacade)
    {
        $this->sharedCartFacade = $sharedCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return void
     */
    public function deleteQuoteCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): void
    {
        $this->sharedCartFacade->deleteQuoteCompanyUser($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return void
     */
    public function updateQuoteCompanyUserPermissionGroup(ShareCartRequestTransfer $shareCartRequestTransfer): void
    {
        $this->sharedCartFacade->updateQuoteCompanyUserPermissionGroup($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCompanyUserTransfer $quoteCompanyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCompanyUserTransfer|null
     */
    public function findQuoteCompanyUserByUuid(QuoteCompanyUserTransfer $quoteCompanyUserTransfer): ?QuoteCompanyUserTransfer
    {
        return $this->sharedCartFacade->findQuoteCompanyUserByUuid($quoteCompanyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareCartRequestTransfer $shareCartRequestTransfer
     *
     * @return void
     */
    public function addQuoteCompanyUser(ShareCartRequestTransfer $shareCartRequestTransfer): void
    {
        $this->sharedCartFacade->addQuoteCompanyUser($shareCartRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function findShareDetailsCollectionByShareDetailCriteria(ShareDetailCriteriaFilterTransfer $shareDetailCriteriaFilterTransfer): ShareDetailCollectionTransfer
    {
        return $this->sharedCartFacade->findShareDetailsCollectionByShareDetailCriteria($shareDetailCriteriaFilterTransfer);
    }
}
