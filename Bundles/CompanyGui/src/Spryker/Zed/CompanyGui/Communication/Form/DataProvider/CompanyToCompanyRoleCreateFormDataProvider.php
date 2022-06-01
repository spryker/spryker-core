<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Form\DataProvider;

use Spryker\Zed\CompanyGui\Communication\Formatter\CompanyNameFormatterInterface;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;

class CompanyToCompanyRoleCreateFormDataProvider
{
    /**
     * @uses \Spryker\Zed\CompanyGui\Communication\Form\CompanyToCompanyRoleCreateForm::OPTION_COMPANY_CHOICES
     *
     * @var string
     */
    protected const OPTION_COMPANY_CHOICES = 'company_choices';

    /**
     * @var \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \Spryker\Zed\CompanyGui\Communication\Formatter\CompanyNameFormatterInterface
     */
    protected $companyNameFormatter;

    /**
     * @param \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface $companyFacade
     * @param \Spryker\Zed\CompanyGui\Communication\Formatter\CompanyNameFormatterInterface $companyNameFormatter
     */
    public function __construct(
        CompanyGuiToCompanyFacadeInterface $companyFacade,
        CompanyNameFormatterInterface $companyNameFormatter
    ) {
        $this->companyFacade = $companyFacade;
        $this->companyNameFormatter = $companyNameFormatter;
    }

    /**
     * @param int|null $idCompany
     *
     * @return array<string, mixed>
     */
    public function getOptions(?int $idCompany = null): array
    {
        return [
            static::OPTION_COMPANY_CHOICES => $this->getCompanyChoices($idCompany),
        ];
    }

    /**
     * @param int|null $idCompany
     *
     * @return array<string, int>
     */
    public function getCompanyChoices(?int $idCompany = null): array
    {
        if (!$idCompany) {
            return [];
        }

        $companyTransfer = $this->companyFacade->findCompanyById($idCompany);

        if (!$companyTransfer) {
            return [];
        }

        return [$this->companyNameFormatter->formatName($companyTransfer) => $idCompany];
    }
}
