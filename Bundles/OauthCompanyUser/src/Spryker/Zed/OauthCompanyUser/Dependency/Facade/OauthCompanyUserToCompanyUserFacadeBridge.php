<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserTransfer;

class OauthCompanyUserToCompanyUserFacadeBridge implements OauthCompanyUserToCompanyUserFacadeInterface
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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByUuid(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer
    {
        return $this->companyUserFacade->findActiveCompanyUserByUuid($companyUserTransfer);
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
}
