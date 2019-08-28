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
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyUserAuthRestApiHelper extends REST
{
    use ModuleLocatorTrait;

    public const DEFAULT_PASSWORD = 'Pass$.123456';

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

        $this->companyProvider = $this->findModule(CompanyHelper::class);
        $this->companyUserProvider = $this->findModule(CompanyUserHelper::class);
        $this->customerProvider = $this->findModule(CustomerDataHelper::class);
    }

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param array $glueToken
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amAuthorizedGlueCompanyUser(array $glueToken, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->haveHttpHeader('X-Company-User-Id', $customerTransfer->getCompanyUserTransfer()->getUuid());
        $this->haveHttpHeader('Authorization', sprintf('%s %s', $glueToken['tokenType'], $glueToken['accessToken']));

        return $customerTransfer;
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
     * TODO: Move to Customer module
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
        return $this->customerProvider
            ? $this->customerProvider->haveCustomer([
                CustomerTransfer::FIRST_NAME => 'John',
                CustomerTransfer::LAST_NAME => 'Doe',
                CustomerTransfer::EMAIL => $withEmail,
                CustomerTransfer::PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
                CustomerTransfer::NEW_PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
            ])
            : $this->haveCustomerByApi($withEmail, $withPassword);
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
        $this->sendPOST('customers', [
            'data' => [
                'type' => 'customers',
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
}
