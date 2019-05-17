<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;
use Propel\Runtime\Collection\ObjectCollection;

interface QuoteShareDetailMapperInterface
{
    /**
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser $quoteCompanyUserEntity
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    public function mapQuoteCompanyUserToShareDetailTransfer(SpyQuoteCompanyUser $quoteCompanyUserEntity): ShareDetailTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser[] $quoteCompanyUserEntities
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroupTransfers
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function mapShareDetailCollection(ObjectCollection $quoteCompanyUserEntities, array $quotePermissionGroupTransfers): ShareDetailCollectionTransfer;
}
