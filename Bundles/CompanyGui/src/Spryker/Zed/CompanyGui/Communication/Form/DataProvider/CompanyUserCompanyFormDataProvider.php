<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Form\DataProvider;

use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;

class CompanyUserCompanyFormDataProvider
{
    /**
     * @var \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @param \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface $companyFacade
     */
    public function __construct(CompanyGuiToCompanyFacadeInterface $companyFacade)
    {
        $this->companyFacade = $companyFacade;
    }

    /**
     * @param int|null $idCompany
     *
     * @return array<string, int>
     */
    public function getOptions(?int $idCompany): array
    {
        if (!$idCompany) {
            return [];
        }

        $companyTransfer = $this->companyFacade->findCompanyById($idCompany);

        if ($companyTransfer) {
            return [$companyTransfer->getName() => $idCompany];
        }

        return [];
    }
}
