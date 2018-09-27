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
            $shareDetailTransfer = $this->mapShareDetailTransfer($quoteCompanyUserEntity, $indexedQuotePermissionGroupTransfers);
            $shareDetailCollectionTransfer->addShareDetail($shareDetailTransfer);
        }

        return $shareDetailCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser $quoteCompanyUserEntity
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $indexedQuotePermissionGroupTransfers
     *
     * @return \Generated\Shared\Transfer\ShareDetailTransfer
     */
    protected function mapShareDetailTransfer(SpyQuoteCompanyUser $quoteCompanyUserEntity, array $indexedQuotePermissionGroupTransfers): ShareDetailTransfer
    {
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setIdQuoteCompanyUser($quoteCompanyUserEntity->getIdQuoteCompanyUser())
            ->setIdCompanyUser($quoteCompanyUserEntity->getFkCompanyUser())
            ->setQuotePermissionGroup();

        $customerEntity = $quoteCompanyUserEntity->getSpyCompanyUser()->getCustomer();
        $shareDetailTransfer->setCustomerName(
            $this->formatCustomerFullName($customerEntity->getLastName(), $customerEntity->getFirstName())
        );

        $shareDetailTransfer->setQuotePermissionGroup(
            $indexedQuotePermissionGroupTransfers[$quoteCompanyUserEntity->getFkQuotePermissionGroup()]
        );

        return $shareDetailTransfer;
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

    /**
     * @param string $lastName
     * @param string $firstName
     *
     * @return string
     */
    protected function formatCustomerFullName(string $lastName, string $firstName): string
    {
        return $lastName . ' ' . $firstName;
    }
}
