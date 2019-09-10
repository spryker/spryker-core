<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CompanyUserAuthRestApi\Helper;

use Codeception\Exception\ModuleException;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use SprykerTest\Glue\Testify\Helper\GlueRest;
use SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyUserAuthRestApiHelper extends GlueRest
{
    /**
     * @var \SprykerTest\Zed\Company\Helper\CompanyHelper|null
     */
    protected $companyProvider;

    /**
     * @var \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper|null
     */
    protected $companyUserProvider;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->companyProvider = $this->getCompanyProvider();
        $this->companyUserProvider = $this->getCompanyUserProvider();
    }

    /**
     * Specification:
     * - Creates company.
     *
     * @part json
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function amCompany(): CompanyTransfer
    {
        return $companyTransfer = $this->companyProvider->haveCompany([
            CompanyTransfer::KEY => uniqid('c-', true),
        ]);
    }

    /**
     * Specification:
     * - Creates company user.
     *
     * @part json
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function amCompanyUserInCompany(int $idCompany): CompanyUserTransfer
    {
        $companyUserUuid = uniqid('cu-', true);
        $companyUserTransfer = $this->companyUserProvider->haveCompanyUser([
            CompanyUserTransfer::KEY => $companyUserUuid,
            CompanyUserTransfer::UUID => $companyUserUuid,
            CompanyUserTransfer::FK_COMPANY => $idCompany,
        ]);

        return $companyUserTransfer;
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Zed\Company\Helper\CompanyHelper
     */
    protected function getCompanyProvider(): CompanyHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CompanyHelper) {
                return $module;
            }
        }

        throw new ModuleException('CompanyUserAuthRestApi', 'The module requires CompanyHelper.');
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper
     */
    protected function getCompanyUserProvider(): CompanyUserHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CompanyUserHelper) {
                return $module;
            }
        }

        throw new ModuleException('CompanyUserAuthRestApi', 'The module requires CompanyUserHelper.');
    }
}
