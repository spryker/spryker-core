<?php
namespace ZedCommunication;

use Faker\Factory;
use Faker\Generator;

class IndexCest
{
    /**
     * @var Generator
     */
    protected $faker;

    public function _inject()
    {
        $this->faker = Factory::create();
    }

    public function listCustomers(\Customer\ZedCommunicationTester $i)
    {
        $i->amOnPage('/customer');
        $i->seeResponseCodeIs(200);
        $i->see('Customers', 'h5');
    }

    /**
     * @group current
     * @param \Customer\ZedCommunicationTester $i
     */
    public function addCustomer(\Customer\ZedCommunicationTester $i)
    {
        $email = $this->faker->email;
        $i->amOnPage('/customer/add');
        $i->submitForm(['name' => 'customer'], ['customer' => [
            'email' => $email,
            'salutation' => 'Mr',
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName
        ]]);
        $i->see(1);
        $i->listDataTable('/customer/index/table');
        $i->seeInLastRow([2 => $email]);
    }
}