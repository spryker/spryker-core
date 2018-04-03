<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyPermissionEntityTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartPersistenceFactory getFactory()
 */
class SharedCartEntityManager extends AbstractEntityManager implements SharedCartEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPermissionEntityTransfer
     */
    public function savePermissionEntity(SpyPermissionEntityTransfer $permissionEntityTransfer): SpyPermissionEntityTransfer
    {
        $permissionEntity = $this->getFactory()
            ->createPermissionQuery()
            ->filterByKey($permissionEntityTransfer->getKey())
            ->findOneOrCreate();

        $permissionEntity->fromArray($permissionEntityTransfer->modifiedToArray());
        $permissionEntity->save();

        $permissionEntityTransfer->fromArray($permissionEntity->toArray(), true);

        return $permissionEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    public function saveQuotePermissionGroupEntity(SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer): SpyQuotePermissionGroupEntityTransfer
    {
        $quotePermissionGroupEntity = $this->getFactory()
            ->createQuotePermissionGroupQuery()
            ->filterByName($quotePermissionGroupEntityTransfer->getName())
            ->findOneOrCreate();

        $quotePermissionGroupEntity->fromArray($quotePermissionGroupEntityTransfer->modifiedToArray());
        $quotePermissionGroupEntity->save();

        $quotePermissionGroupEntityTransfer->fromArray($quotePermissionGroupEntity->toArray(), true);

        return $quotePermissionGroupEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer
     * @param \Generated\Shared\Transfer\SpyPermissionEntityTransfer $permissionEntityTransfer
     *
     * @return void
     */
    public function saveQuotePermissionGroupToPermissionEntity(
        SpyQuotePermissionGroupEntityTransfer $quotePermissionGroupEntityTransfer,
        SpyPermissionEntityTransfer $permissionEntityTransfer
    ): void {
        $this->getFactory()
            ->createQuotePermissionGroupToPermissionQuery()
            ->filterByFkQuotePermissionGroup($quotePermissionGroupEntityTransfer->getIdQuotePermissionGroup())
            ->filterByFkPermission($permissionEntityTransfer->getIdPermission())
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
