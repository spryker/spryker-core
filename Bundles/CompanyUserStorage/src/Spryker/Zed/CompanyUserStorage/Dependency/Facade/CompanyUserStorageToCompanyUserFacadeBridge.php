<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Dependency\Facade;

class CompanyUserStorageToCompanyUserFacadeBridge implements CompanyUserStorageToCompanyUserFacadeInterface
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
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByIds(array $companyUserIds): array
    {
        return $this->companyUserFacade->findActiveCompanyUsersByIds($companyUserIds);
    }

    /**
     * @param int[] $companyIds
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array
    {
        return $this->companyUserFacade->findActiveCompanyUserIdsByCompanyIds($companyIds);
    }
}
