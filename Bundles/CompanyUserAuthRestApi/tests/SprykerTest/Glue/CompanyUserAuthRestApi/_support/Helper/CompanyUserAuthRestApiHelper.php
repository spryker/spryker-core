<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CompanyUserAuthRestApi\Helper;

use Codeception\Exception\ModuleException;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Glue\Testify\Helper\GlueRest;
use SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyUserAuthRestApiHelper extends GlueRest
{
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

        $this->companyProvider = $this->getCompanyProvider();
        $this->companyUserProvider = $this->getCompanyUserProvider();
        $this->customerProvider = $this->findCustomerProvider();
    }

    /**
     * Specification:
     * - Creates company, company user and customer.
     *
     * @part json
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUser(): CustomerTransfer
    {
        $company = uniqid('c-', true);
        $companyTransfer = $this->companyProvider->haveCompany([
            CompanyTransfer::KEY => $company,
        ]);

        return $this->amCompanyUserInCompany($companyTransfer->getIdCompany());
    }

    /**
     * Specification:
     * - Creates customer.
     *
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

        if (!$this->customerProvider) {
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
     * Specification:
     * - Creates company user and customer.
     *
     * @part json
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUserInCompany(int $idCompany): CustomerTransfer
    {
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
     * Specification:
     * - Creates customer via REST API.
     *
     * @part json
     *
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomerByApi(string $withEmail, string $withPassword = ''): CustomerTransfer
    {
        $customerTransfer = $this->createCustomerTransfer($withEmail, $withPassword);

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

        $customerTransfer->setIdCustomer(
            $this->grabDataFromResponseByJsonPath('$.data.id')[0]
        );

        return $customerTransfer;
    }

    /**
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(string $withEmail, string $withPassword): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail($withEmail)
            ->setNewPassword($withPassword ?: static::DEFAULT_PASSWORD);
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

        throw new ModuleException('GlueRest', 'The module requires CompanyHelper');
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper|null
     */
    protected function getCompanyUserProvider(): ?CompanyUserHelper
    {
        foreach ($this->getModules() as $module) {
            if ($module instanceof CompanyUserHelper) {
                return $module;
            }
        }

        throw new ModuleException('GlueRest', 'The module requires CompanyUserHelper');
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
