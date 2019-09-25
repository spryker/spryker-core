<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\CustomersRestApi\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use SprykerTest\Glue\Testify\Helper\GlueRest;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;

class CustomersRestApiHelper extends Module
{
    protected const FIRST_NAME = 'John';

    protected const LAST_NAME = 'Doe';

    protected const SALUTATION = 'Mr';

    /**
     * @var \SprykerTest\Glue\Testify\Helper\GlueRest|null
     */
    protected $glueRestProvider;

    /**
     * @var \SprykerTest\Shared\Customer\Helper\CustomerDataHelper|null
     */
    protected $customerProvider;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        $this->customerProvider = $this->findCustomerProvider();
        $this->glueRestProvider = $this->getGlueRestProvider();
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
            CustomerTransfer::FIRST_NAME => static::FIRST_NAME,
            CustomerTransfer::LAST_NAME => static::LAST_NAME,
            CustomerTransfer::EMAIL => $withEmail,
            CustomerTransfer::PASSWORD => $withPassword ?: $this->glueRestProvider::DEFAULT_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => $withPassword ?: $this->glueRestProvider::DEFAULT_PASSWORD,
        ]);
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

        $this->glueRestProvider->sendPOST(CustomersRestApiConfig::RESOURCE_CUSTOMERS, [
            'data' => [
                'type' => CustomersRestApiConfig::RESOURCE_CUSTOMERS,
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

        $customerReference = $this->glueRestProvider->grabDataFromResponseByJsonPath('$.data.id')[0];
        if (!$customerReference) {
            return $customerTransfer;
        }

        $customerTransfer->setIdCustomer($customerReference);

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
        return (new CustomerBuilder([
            CustomerTransfer::FIRST_NAME => static::FIRST_NAME,
            CustomerTransfer::LAST_NAME => static::LAST_NAME,
            CustomerTransfer::SALUTATION => static::SALUTATION,
            CustomerTransfer::EMAIL => $withEmail,
            CustomerTransfer::NEW_PASSWORD => $withPassword ?: $this->glueRestProvider::DEFAULT_PASSWORD,
        ]))->build();
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

        throw new ModuleException('CustomersRestApi', 'The module requires GlueRest.');
    }
}
