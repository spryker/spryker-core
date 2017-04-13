<?php

namespace ZedCommunication;

use Codeception\Util\Stub;
use Customer\ZedCommunicationTester;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;

/**
 * Auto-generated group annotations
 * @group Customer
 * @group ZedCommunication
 * @group IndexControllerCest
 * Add your own group annotations below this line
 */
class IndexControllerCest
{

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function _before(ZedCommunicationTester $i)
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
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function listCustomers(ZedCommunicationTester $i)
    {
        $i->amOnPage('/customer');
        $i->seeResponseCodeIs(200);
        $i->see('Customers', 'h5');
    }

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function addCustomer(ZedCommunicationTester $i)
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

        $i->amOnPage('/customer/add');
        $i->submitForm(['name' => 'customer'], $formData);

        $i->listDataTable('/customer/index/table');
        $i->seeInLastRow([2 => $email]);
    }

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function addCustomerWithoutNameAndFail(ZedCommunicationTester $i)
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

        $i->amOnPage('/customer/add');
        $i->submitForm(['name' => 'customer'], $formData);
        $i->expect('I am back on the form page');
        $i->seeCurrentUrlEquals('/customer/add');
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
