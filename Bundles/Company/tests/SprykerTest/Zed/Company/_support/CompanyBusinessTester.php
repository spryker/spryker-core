<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Company;

use Codeception\Actor;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Company\Persistence\CompanyRepository;

/**
 * Inherited Methods
 *
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
 * @method \Spryker\Zed\Company\Business\CompanyFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyBusinessTester extends Actor
{
    use _generated\CompanyBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param int $idCompany
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByIdCompany(int $idCompany)
    {
        return (new CompanyRepository())
            ->getRelatedStoresByCompanyId($idCompany);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer
    {
        return $this->getLocator()->store()->facade()->getCurrentStore();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores(): array
    {
        return $this->getLocator()->store()->facade()->getAllStores();
    }
}
