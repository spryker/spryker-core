<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CompanyUserAuthRestApi\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\REST;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyUserAuthRestApiHelper extends REST
{
    public const DEFAULT_PASSWORD = 'Pass$.123456';

    protected const RESOURCE_CUSTOMERS = 'customers';

    /**
     * @var \SprykerTest\Zed\Company\Helper\CompanyHelper|null
     */
    protected $companyProvider;

    /**
     * @var \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper|null
     */
    protected $companyUserProvider;

    /**
     * @var \SprykerTest\Shared\Customer\Helper\CustomerDataHelper|null
     */
    protected $customerProvider;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->companyProvider = $this->findCompanyProvider();
        $this->companyUserProvider = $this->findCompanyUserProvider();
        $this->customerProvider = $this->findCustomerProvider();
    }

    /**
     * Creates company, company user and customer
     *
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUser(): CustomerTransfer
    {
        if ($this->companyProvider === null) {
            throw new ModuleException('GlueRest', 'The module requires CompanyHelper');
        }
        $company = uniqid('c-', true);
        $companyTransfer = $this->companyProvider->haveCompany([
            CompanyTransfer::KEY => $company,
        ]);

        return $this->amCompanyUserInCompany($companyTransfer->getIdCompany());
    }

    /**
     * @part json
     *
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amUser(string $withEmail = '', string $withPassword = ''): CustomerTransfer
    {
        $withEmail = $withEmail ?: sprintf('%s@test.local.com', uniqid('glue-', true));

        if ($this->customerProvider === null) {
            return $this->haveCustomerByApi($withEmail, $withPassword);
        }

        return $this->customerProvider->haveCustomer([
            CustomerTransfer::FIRST_NAME => 'John',
            CustomerTransfer::LAST_NAME => 'Doe',
            CustomerTransfer::EMAIL => $withEmail,
            CustomerTransfer::PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
        ]);
    }

    /**
     * Creates company user and customer
     *
     * @part json
     *
     * @param int $idCompany
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUserInCompany(int $idCompany): CustomerTransfer
    {
        if ($this->companyUserProvider === null) {
            throw new ModuleException('GlueRest', 'The module requires CompanyUserHelper');
        }
        $companyUserUuid = uniqid('cu-', true);
        $customerTransfer = $this->amUser();
        $companyUserTransfer = $this->companyUserProvider->haveCompanyUser([
            CompanyUserTransfer::KEY => $companyUserUuid,
            CompanyUserTransfer::UUID => $companyUserUuid,
            CompanyUserTransfer::FK_COMPANY => $idCompany,
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        return $this->removeCyclicLinksInCustomerTransfer($customerTransfer);
    }

    /**
     * @part json
     *
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomerByApi(string $withEmail, string $withPassword = ''): CustomerTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail($withEmail)
            ->setNewPassword($withPassword ?: static::DEFAULT_PASSWORD);
        $this->sendPOST(static::RESOURCE_CUSTOMERS, [
            'data' => [
                'type' => static::RESOURCE_CUSTOMERS,
                'attributes' => [
                    'salutation' => $customerTransfer->getSalutation(),
                    'firstName' => $customerTransfer->getFirstName(),
                    'lastName' => $customerTransfer->getLastName(),
                    'email' => $customerTransfer->getEmail(),
                    'password' => $customerTransfer->getNewPassword(),
                    'confirmPassword' => $customerTransfer->getNewPassword(),
                    'acceptedTerms' => true,
                ],
            ],
        ]);
        $this->seeResponseCodeIs(HttpCode::CREATED);
        $customerTransfer->setIdCustomer(
            $this->grabDataFromResponseByJsonPath('$.data.id')[0]
        );

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function removeCyclicLinksInCustomerTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer()) {
            $customerTransfer->getCompanyUserTransfer()->setCustomer();
        }

        return $customerTransfer;
    }

    /**
     * @return \SprykerTest\Zed\Company\Helper\CompanyHelper|null
     */
    protected function findCompanyProvider(): ?CompanyHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CompanyHelper) {
                return $module;
            }
        }

        return null;
    }

    /**
     * @return \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper|null
     */
    protected function findCompanyUserProvider(): ?CompanyUserHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CompanyUserHelper) {
                return $module;
            }
        }

        return null;
    }

    /**
     * @return \SprykerTest\Shared\Customer\Helper\CustomerDataHelper|null
     */
    protected function findCustomerProvider(): ?CustomerDataHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CustomerDataHelper) {
                return $module;
            }
        }

        return null;
    }
}
