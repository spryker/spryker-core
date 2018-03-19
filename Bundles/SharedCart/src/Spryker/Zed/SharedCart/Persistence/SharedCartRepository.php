<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartPersistenceFactory getFactory()
 */
class SharedCartRepository extends AbstractRepository implements SharedCartRepositoryInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        // TODO: refactor
        $quotePermissionGroupToPermissionEntities = $this->getFactory()
            ->createQuotePermissionGroupToPermissionQuery()
            ->joinWithPermission()
            ->useQuotePermissionGroupQuery()
                ->useSpyQuoteCompanyUserQuery()
                    ->filterByFkCompanyUser($idCompanyUser)
                ->endUse()
            ->endUse()
            ->groupByFkPermission()
            ->find();

        $permissionCollectionTransfer = new PermissionCollectionTransfer();
        foreach ($quotePermissionGroupToPermissionEntities as $quotePermissionGroupToPermissionEntity) {
            $permissionTransfer = new PermissionTransfer();

            $permissionTransfer->setKey($quotePermissionGroupToPermissionEntity->getPermission()->getKey());

            $quoteCompanyUserEntities = $quotePermissionGroupToPermissionEntity->getQuotePermissionGroup()->getSpyQuoteCompanyUsers();

            $idQuoteCollection = [];
            foreach ($quoteCompanyUserEntities as $quoteCompanyUserEntity) {
                $idQuoteCollection[] = $quoteCompanyUserEntity->getFkQuote();
            }

            $permissionTransfer->setConfiguration([
                'id_quote_collection' => $idQuoteCollection,
            ]);

            $permissionCollectionTransfer->addPermission($permissionTransfer);
        }

        return $permissionCollectionTransfer;
    }
}
