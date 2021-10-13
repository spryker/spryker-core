<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Dependency\Facade;

class ShoppingListStorageToCompanyUserFacadeBridge implements ShoppingListStorageToCompanyUserFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct($companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param array<int> $companyUserIds
     *
     * @return array<string>
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        return $this->companyUserFacade->getCustomerReferencesByCompanyUserIds($companyUserIds);
    }
}
