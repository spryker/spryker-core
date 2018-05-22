<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartPersistenceFactory getFactory()
 */
class SharedCartEntityManager extends AbstractEntityManager implements SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function savePermission(PermissionTransfer $permissionTransfer): PermissionTransfer
    {
        $permissionEntity = $this->getFactory()
            ->createPermissionQuery()
            ->filterByKey($permissionTransfer->getKey())
            ->findOneOrCreate();

        $permissionEntity->fromArray($permissionTransfer->modifiedToArray());
        $permissionEntity->save();

        $permissionTransfer->fromArray($permissionEntity->toArray(), true);

        return $permissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    public function saveQuotePermissionGroup(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): QuotePermissionGroupTransfer
    {
        $quotePermissionGroupEntity = $this->getFactory()
            ->createQuotePermissionGroupQuery()
            ->filterByName($quotePermissionGroupTransfer->getName())
            ->findOneOrCreate();

        $quotePermissionGroupEntity->fromArray($quotePermissionGroupTransfer->modifiedToArray());
        $quotePermissionGroupEntity->save();

        $quotePermissionGroupTransfer->fromArray($quotePermissionGroupEntity->toArray(), true);

        return $quotePermissionGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermission(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer,
        PermissionTransfer $permissionTransfer
    ): void {
        $this->getFactory()
            ->createQuotePermissionGroupToPermissionQuery()
            ->filterByFkQuotePermissionGroup($quotePermissionGroupTransfer->getIdQuotePermissionGroup())
            ->filterByFkPermission($permissionTransfer->getIdPermission())
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer $companyUserEntityTransfer
     *
     * @return void
     */
    public function saveQuoteCompanyUser(SpyQuoteCompanyUserEntityTransfer $companyUserEntityTransfer): void
    {
        $this->save($companyUserEntityTransfer);
    }

    /**
     * @param int $idQuoteCompanyUser
     *
     * @return void
     */
    public function deleteQuoteCompanyUser(int $idQuoteCompanyUser): void
    {
        $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByIdQuoteCompanyUser($idQuoteCompanyUser)
            ->delete();
    }

    /**
     * @param int $idCompanyUser
     * @param int $idQuote
     *
     * @return void
     */
    public function setQuoteDefault(int $idCompanyUser, int $idQuote): void
    {
        $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($idQuote)
            ->filterByFkCompanyUser($idCompanyUser)
            ->update(['IsDefault' => true]);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function resetQuoteDefaultFlagByCustomer(int $idCompanyUser): void
    {
        $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkCompanyUser($idCompanyUser)
            ->update(['IsDefault' => false]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function deleteQuoteCompanyUserByQuote(QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createQuoteCompanyUserQuery()
            ->filterByFkQuote($quoteTransfer->getIdQuote())
            ->delete();
    }
}
