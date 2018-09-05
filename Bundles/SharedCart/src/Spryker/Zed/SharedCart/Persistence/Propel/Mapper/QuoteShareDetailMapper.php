<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class QuoteShareDetailMapper implements QuoteShareDetailMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser[] $quoteCompanyUserEntities
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroupTransfers
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function mapShareDetailCollection(ObjectCollection $quoteCompanyUserEntities, array $quotePermissionGroupTransfers): ShareDetailCollectionTransfer
    {
        $shareDetailCollectionTransfer = new ShareDetailCollectionTransfer();
        $indexedQuotePermissionGroupTransfers = $this->indexQuotePermissionGroupById($quotePermissionGroupTransfers);
        foreach ($quoteCompanyUserEntities as $quoteCompanyUserEntity) {
            $shareDetailTransfer = (new ShareDetailTransfer())
                ->setIdQuoteCompanyUser($quoteCompanyUserEntity->getIdQuoteCompanyUser())
                ->setIdCompanyUser($quoteCompanyUserEntity->getFkCompanyUser())
                ->setQuotePermissionGroup();

            $customerEntity = $quoteCompanyUserEntity->getSpyCompanyUser()->getCustomer();
            $shareDetailTransfer->setCustomerName(
                $customerEntity->getLastName() . ' ' . $customerEntity->getFirstName()
            );

            $shareDetailTransfer->setQuotePermissionGroup(
                $indexedQuotePermissionGroupTransfers[$quoteCompanyUserEntity->getFkQuotePermissionGroup()]
            );

            $shareDetailCollectionTransfer->addShareDetail($shareDetailTransfer);
        }

        return $shareDetailCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroupTransfers
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    protected function indexQuotePermissionGroupById(array $quotePermissionGroupTransfers): array
    {
        $indexedQuotePermissionGroupTransfers = [];
        foreach ($quotePermissionGroupTransfers as $quotePermissionGroupTransfer) {
            $indexedQuotePermissionGroupTransfers[$quotePermissionGroupTransfer->getIdQuotePermissionGroup()] = $quotePermissionGroupTransfer;
        }

        return $indexedQuotePermissionGroupTransfers;
    }
}
