<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;

class CartPermissionGroupsRestApiToSharedCartClientBridge implements CartPermissionGroupsRestApiToSharedCartClientInterface
{
    /**
     * @var \Spryker\Client\SharedCart\SharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @param \Spryker\Client\SharedCart\SharedCartClientInterface $sharedCartClient
     */
    public function __construct($sharedCartClient)
    {
        $this->sharedCartClient = $sharedCartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer
     */
    public function getQuotePermissionGroupList(QuotePermissionGroupCriteriaFilterTransfer $criteriaFilterTransfer): QuotePermissionGroupResponseTransfer
    {
        return $this->sharedCartClient->getQuotePermissionGroupList($criteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupResponseTransfer
     */
    public function findQuotePermissionGroupById(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): QuotePermissionGroupResponseTransfer
    {
        return $this->sharedCartClient->findQuotePermissionGroupById($quotePermissionGroupTransfer);
    }
}
