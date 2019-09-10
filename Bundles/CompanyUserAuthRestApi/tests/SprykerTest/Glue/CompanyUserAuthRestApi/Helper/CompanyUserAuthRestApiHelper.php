<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CompanyUserAuthRestApi\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig;
use SprykerTest\Glue\Testify\Helper\GlueRest;
use SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyUserAuthRestApiHelper extends Module
{
    /**
     * @var \SprykerTest\Zed\Company\Helper\CompanyHelper|null
     */
    protected $companyProvider;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\GlueRest|null
     */
    protected $glueRestProvider;

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
        $this->glueRestProvider = $this->getGlueRestProvider();
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
     * Specification:
     * - Authorizes company user and returns access token data.
     * - Returns empty array if authorization failed.
     *
     * @part json
     *
     * @param string $idCompanyUser
     *
     * @return array
     */
    public function haveAuthorizationToGlueAsCompanyUser(string $idCompanyUser): ?array
    {
        $this->glueRestProvider->sendPOST(CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS, [
            'data' => [
                'type' => CompanyUserAuthRestApiConfig::RESOURCE_COMPANY_USER_ACCESS_TOKENS,
                'attributes' => [
                    'idCompanyUser' => $idCompanyUser,
                ],
            ],
        ]);

        return $this->glueRestProvider->grabDataFromResponseByJsonPath('$.data.attributes')[0] ?: [];
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
     * @return \SprykerTest\Glue\Testify\Helper\GlueRest
     */
    protected function getGlueRestProvider(): GlueRest
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof GlueRest) {
                return $module;
            }
        }

        throw new ModuleException('CompanyUserAuthRestApi', 'The module requires GlueRest.');
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
