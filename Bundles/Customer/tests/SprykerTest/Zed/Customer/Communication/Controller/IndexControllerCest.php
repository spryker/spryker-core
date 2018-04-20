<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Controller;

use Codeception\Util\Stub;

use Generated\Shared\DataBuilder\CustomerBuilder;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use SprykerTest\Zed\Customer\CustomerCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Controller
 * @group IndexControllerCest
 * Add your own group annotations below this line
 */
class IndexControllerCest
{
    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function _before(CustomerCommunicationTester $i)
    {
        $i->setDependency(CustomerDependencyProvider::FACADE_MAIL, $this->getMailFacadeMock());
    }

    /**
     * @return object|\Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface
     */
    private function getMailFacadeMock()
    {
        return Stub::makeEmpty(CustomerToMailInterface::class);
    }

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function listCustomers(CustomerCommunicationTester $i)
    {
        $i->amOnPage('/customer');
        $i->seeResponseCodeIs(200);
        $i->see('Customers', 'h5');
    }

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function addCustomer(CustomerCommunicationTester $i)
    {
        $customerTransfer = $this->getCustomerTransfer();

        $email = $customerTransfer->getEmail();

        $formData = [
            'customer' => [
                'email' => $email,
                'salutation' => $customerTransfer->getSalutation(),
                'first_name' => $customerTransfer->getFirstName(),
                'last_name' => $customerTransfer->getLastName(),
            ],
        ];

        $i->amOnPage('/customer/add?redirectUrl=' . urlencode('/customer'));
        $i->submitForm(['name' => 'customer'], $formData);

        $i->listDataTable('/customer/index/table');
        $i->seeInLastRow([2 => $email]);
    }

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function addCustomerWithoutNameAndFail(CustomerCommunicationTester $i)
    {
        $customerTransfer = $this->getCustomerTransfer();
        $email = $customerTransfer->getEmail();

        $formData = [
            'customer' => [
                'email' => $email,
                'salutation' => $customerTransfer->getSalutation(),
                'last_name' => $customerTransfer->getLastName(),
            ],
        ];

        $i->amOnPage('/customer/add?redirectUrl=' . urlencode('/customer'));
        $i->submitForm(['name' => 'customer'], $formData);
        $i->expect('I am back on the form page');
        $i->seeCurrentUrlEquals('/customer/add?redirectUrl=' . urlencode('/customer'));
        $i->see('This value should not be blank.', '#customer');
        $i->listDataTable('/customer/index/table');
        $i->dontSeeInLastRow([2 => $email]);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    private function getCustomerTransfer()
    {
        $customerBuilder = new CustomerBuilder();

        return $customerBuilder->build();
    }
}
