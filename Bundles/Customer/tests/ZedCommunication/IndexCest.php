<?php
namespace ZedCommunication;

use Customer\ZedCommunicationTester;
use Faker\Factory;

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
            ]
        ];

        $i->amOnPage('/customer/add');
        $i->submitForm(['name' => 'customer'], $data);
        $i->see(1);
        $i->listDataTable('/customer/index/table');
        $i->seeInLastRow([2 => $email]);
    }

}
