<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyGui\Communication\Form\CompanyForm;
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
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getData(int $idCompany)
    {
        return $this->companyFacade->getCompanyById($this->createCompanyTransfer()->setIdCompany($idCompany));
    }

    /**
     * @param int $idCompany
     *
     * @return array
     */
    public function getOptions(int $idCompany)
    {
        return [
            CompanyForm::OPTION_COMPANY_TYPE_CHOICES => $this->prepareOptions(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyTransfer()
    {
        return new CompanyTransfer();
    }

    /**
     * @return array
     */
    protected function prepareOptions(): array
    {
        $result = [];

        foreach ($this->companyFacade->getCompanyTypes()->getCompanyTypes() as $companyType) {
            $result[$companyType->getIdCompanyType()] = $companyType->getName();
        }

        return $result;
    }
}
