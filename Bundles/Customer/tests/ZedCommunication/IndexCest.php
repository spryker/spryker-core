<?php

namespace ZedCommunication;

use Codeception\Util\Stub;
use Customer\ZedCommunicationTester;
use Faker\Factory;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Mail\Business\MailFacadeInterface;

/**
 * Auto-generated group annotations
 * @group Customer
 * @group ZedCommunication
 * @group IndexCest
 * Add your own group annotations below this line
 */
class IndexCest
{

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @return void
     */
    public function _inject()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function _before(ZedCommunicationTester $i)
    {
        $customerToMailBridge = new CustomerToMailBridge($this->getMailFacadeMock());

        $i->setDependency(CustomerDependencyProvider::FACADE_MAIL, $customerToMailBridge);
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface|object
     */
    private function getMailFacadeMock()
    {
        return Stub::makeEmpty(MailFacadeInterface::class);
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
        $email = $this->faker->email;

        $formData = [
            'customer' => [
                'email' => $email,
                'salutation' => 'Mr',
                'first_name' => $this->faker->firstName,
                'last_name' => $this->faker->lastName,
            ],
        ];

        $i->amOnPage('/customer/add');
        $i->submitForm(['name' => 'customer'], $formData);

        $i->listDataTable('/customer/index/table');
        $i->seeInLastRow([2 => $email]);
    }

    /**
     * @group current
     *
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function addCustomerWithoutNameAndFail(ZedCommunicationTester $i)
    {
        $email = $this->faker->email;

        $formData = [
            'customer' => [
                'email' => $email,
                'salutation' => 'Mr',
                'last_name' => $this->faker->lastName,
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

}
