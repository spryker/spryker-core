<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Communication\Controller;

use SprykerTest\Zed\Customer\CustomerCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Communication
 * @group Controller
 * @group EditControllerCest
 * Add your own group annotations below this line
 */
class EditControllerCest
{
    public const NEW_FIRST_NAME = 'superMan';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    private $customer;

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function _before(CustomerCommunicationTester $i)
    {
        $this->customer = $i->haveCustomer();
    }

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function testIndexAction(CustomerCommunicationTester $i)
    {
        $url = '/customer/edit?id-customer=' . (int)$this->customer->getIdCustomer();
        $i->amOnPage($url);
        $i->seeResponseCodeIs(200);
        $i->see('Edit Customer', 'h5');
    }

    /**
     * @param \SprykerTest\Zed\Customer\CustomerCommunicationTester $i
     *
     * @return void
     */
    public function testEditUser(CustomerCommunicationTester $i)
    {
        $email = $this->customer->getEmail();

        $formData = [
            'customer' => [
                'email' => $email,
                'salutation' => $this->customer->getSalutation(),
                'first_name' => static::NEW_FIRST_NAME,
                'last_name' => $this->customer->getLastName(),
            ],
        ];

        $url = '/customer/edit?id-customer=' . (int)$this->customer->getIdCustomer();
        $i->amOnPage($url);
        $i->submitForm(['name' => 'customer'], $formData);

        $customerTransfer = $i->getLocator()->customer()->facade()->getCustomer($this->customer);
        $i->assertSame(static::NEW_FIRST_NAME, $customerTransfer->getFirstName());
    }
}
