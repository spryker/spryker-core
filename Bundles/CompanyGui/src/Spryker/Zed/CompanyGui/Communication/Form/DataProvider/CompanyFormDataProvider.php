<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;

class CompanyFormDataProvider
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
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getData(?int $idCompany = null): CompanyTransfer
    {
        $companyTransfer = new CompanyTransfer();

        if (!$idCompany) {
            return $companyTransfer;
        }

        return $this->companyFacade->findCompanyById($idCompany) ?? $companyTransfer;
    }

    /**
     * @param int|null $idCompany
     *
     * @return array
     */
    public function getOptions(?int $idCompany = null): array
    {
        return [
            'data_class' => CompanyTransfer::class,
        ];
    }
}
