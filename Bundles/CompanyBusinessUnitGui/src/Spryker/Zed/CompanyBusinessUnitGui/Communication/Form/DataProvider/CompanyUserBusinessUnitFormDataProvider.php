<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;

class CompanyUserBusinessUnitFormDataProvider
{
    /**
     * @var string
     */
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';

    /**
     * @var string
     */
    protected const FORMAT_NAME = '%s (id: %s)';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface
     */
    protected $companyBusinessUnitNameGenerator;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface $generator
     */
    public function __construct(
        CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyBusinessUnitNameGeneratorInterface $generator
    ) {
        $this->companyBusinessUnitNameGenerator = $generator;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param int|null $idCompanyBusinessUnit
     *
     * @return int[]
     */
    public function getOptions(?int $idCompanyBusinessUnit = null): array
    {
        if (!$idCompanyBusinessUnit) {
            return [];
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($idCompanyBusinessUnit);

        if ($companyBusinessUnitTransfer) {
            $companyBusinessUnitName = $this->generateCompanyBusinessUnitName($companyBusinessUnitTransfer);

            return [$companyBusinessUnitName => $idCompanyBusinessUnit];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function generateCompanyBusinessUnitName(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return $this->companyBusinessUnitNameGenerator->generateName($companyBusinessUnitTransfer);
    }
}
