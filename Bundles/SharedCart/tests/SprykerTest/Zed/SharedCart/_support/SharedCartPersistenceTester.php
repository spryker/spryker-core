<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart;

use Codeception\Actor;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUser;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUser;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SharedCartPersistenceTester extends Actor
{
    use _generated\SharedCartPersistenceTesterActions;

    public const MAPPING_KEY = 1;

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getQuoteCompanyUserCollection(): ObjectCollection
    {
        $quoteCompanyUserEntity = new SpyQuoteCompanyUser();
        $quoteCompanyUserEntity->setFkQuote(static::MAPPING_KEY);
        $quoteCompanyUserEntity->setFkQuotePermissionGroup(static::MAPPING_KEY);

        $spyCustomer = new SpyCustomer();
        $spyCustomer->setFirstName('Cat');
        $spyCustomer->setLastName('Face');

        $spyCompanyUserEntity = new SpyCompanyUser();
        $spyCompanyUserEntity->setCustomer($spyCustomer);
        $quoteCompanyUserEntity->setSpyCompanyUser($spyCompanyUserEntity);

        $quoteCompanyUserEntities = new ObjectCollection();
        $quoteCompanyUserEntities->append($quoteCompanyUserEntity);

        return $quoteCompanyUserEntities;
    }

    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    public function getQuotePermissionGroupTransferCollection(): array
    {
        $quotePermissionGroupTransfer = new QuotePermissionGroupTransfer();
        $quotePermissionGroupTransfer->setIdQuotePermissionGroup(static::MAPPING_KEY);

        return [
            $quotePermissionGroupTransfer,
        ];
    }
}
