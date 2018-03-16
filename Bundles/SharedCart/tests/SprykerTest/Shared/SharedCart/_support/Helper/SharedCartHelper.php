<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SharedCart\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyQuoteRoleEntityTransfer;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;
use Orm\Zed\SharedCart\Persistence\SpyQuoteRole;
use Orm\Zed\SharedCart\Persistence\SpyQuoteRoleToPermission;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SharedCartHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param string $name
     * @param array $permissionKeys
     *
     * @return \Generated\Shared\Transfer\SpyQuoteRoleEntityTransfer
     */
    public function haveQuoteRole($name, array $permissionKeys)
    {
        $quoteRoleEntity = new SpyQuoteRole();
        $quoteRoleEntity->setName($name);

        foreach ($permissionKeys as $permissionKey) {
            $permissionEntity = SpyPermissionQuery::create()
                ->filterByKey($permissionKey)
                ->findOneOrCreate();

//            $permissionEntity->save();
            $quoteRoleToPermissionEntity = new SpyQuoteRoleToPermission();
            $quoteRoleToPermissionEntity
                ->setPermission($permissionEntity);

            $quoteRoleEntity->addSpyQuoteRoleToPermission($quoteRoleToPermissionEntity);
        }

        $quoteRoleEntity->save();

        $quoteRoleEntityTransfer = new SpyQuoteRoleEntityTransfer();
        $quoteRoleEntityTransfer->fromArray($quoteRoleEntity->toArray(), true);

        return $quoteRoleEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpyQuoteRoleEntityTransfer $spyQuoteRoleEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer
     */
    public function haveQuoteCompanyUser(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        SpyQuoteRoleEntityTransfer $spyQuoteRoleEntityTransfer
    ) {
        $quoteCompanyUserEntity = new SpyQuoteCompanyUser();
        $quoteCompanyUserEntity
            ->setFkCompanyUser($companyUserTransfer->requireIdCompanyUser()->getIdCompanyUser())
            ->setFkQuote($quoteTransfer->requireIdQuote()->getIdQuote())
            ->setFkQuoteRole($spyQuoteRoleEntityTransfer->requireIdQuoteRole()->getIdQuoteRole())
            ->save();

        $quoteCompanyUserEntityTransfer = new SpyQuoteCompanyUserEntityTransfer();
        $quoteCompanyUserEntityTransfer->fromArray($quoteCompanyUserEntity->toArray(), true);

        return $quoteCompanyUserEntityTransfer;
    }
}
