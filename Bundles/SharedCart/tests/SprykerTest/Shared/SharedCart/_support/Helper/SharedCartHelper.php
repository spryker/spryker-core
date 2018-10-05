<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SharedCart\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroup;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermission;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SharedCartHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param string $name
     * @param array $permissionKeys
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    public function haveQuotePermissionGroup($name, array $permissionKeys)
    {
        $quotePermissionGroupEntity = new SpyQuotePermissionGroup();
        $quotePermissionGroupEntity->setName($name);

        foreach ($permissionKeys as $permissionKey) {
            $permissionEntity = SpyPermissionQuery::create()
                ->filterByKey($permissionKey)
                ->findOneOrCreate();

            $quotePermissionGroupToPermissionEntity = new SpyQuotePermissionGroupToPermission();
            $quotePermissionGroupToPermissionEntity
                ->setPermission($permissionEntity);

            $quotePermissionGroupEntity->addSpyQuotePermissionGroupToPermission($quotePermissionGroupToPermissionEntity);
        }

        $quotePermissionGroupEntity->save();

        $quotePermissionGroupEntityTransfer = new SpyQuotePermissionGroupEntityTransfer();
        $quotePermissionGroupEntityTransfer->fromArray($quotePermissionGroupEntity->toArray(), true);

        return $quotePermissionGroupEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer $spyQuotePermissionGroupEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer
     */
    public function haveQuoteCompanyUser(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        SpyQuotePermissionGroupEntityTransfer $spyQuotePermissionGroupEntityTransfer
    ) {
        $quoteCompanyUserEntity = new SpyQuoteCompanyUser();
        $quoteCompanyUserEntity
            ->setFkCompanyUser($companyUserTransfer->requireIdCompanyUser()->getIdCompanyUser())
            ->setFkQuote($quoteTransfer->requireIdQuote()->getIdQuote())
            ->setFkQuotePermissionGroup($spyQuotePermissionGroupEntityTransfer->requireIdQuotePermissionGroup()->getIdQuotePermissionGroup())
            ->save();

        $quoteCompanyUserEntityTransfer = new SpyQuoteCompanyUserEntityTransfer();
        $quoteCompanyUserEntityTransfer->fromArray($quoteCompanyUserEntity->toArray(), true);

        return $quoteCompanyUserEntityTransfer;
    }
}
