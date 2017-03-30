<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedCommunication\Controller;

use Codeception\Util\Stub;
use Customer\ZedCommunicationTester;
use Spryker\Zed\Twig\TwigConfig;

/**
 * Auto-generated group annotations
 * @group Customer
 * @group ZedCommunication
 * @group Controller
 * @group EditControllerCest
 * Add your own group annotations below this line
 */
class EditControllerCest
{

    const NEW_FIRST_NAME = 'superMan';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    private $customer;

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function _before(ZedCommunicationTester $i)
    {
        $this->customer = $i->haveCustomer();
        $twigBundleConfigMock = $this->getTwigBundleConfigMock();

        $i->addBundleConfigMock($twigBundleConfigMock);
    }

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testIndexAction(ZedCommunicationTester $i)
    {
        $url = '/customer/edit?id-customer=' . (int)$this->customer->getIdCustomer();
        $i->amOnPage($url);
        $i->seeResponseCodeIs(200);
        $i->see('Edit Customer', 'h5');
    }

    /**
     * @param \Customer\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testEditUser(ZedCommunicationTester $i)
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

    /**
     * @return object|\Spryker\Shared\Kernel\AbstractBundleConfig|\PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    protected function getTwigBundleConfigMock()
    {
        $twigConfig = new TwigConfig();
        $twigBundleConfigMock = Stub::make(TwigConfig::class, [
            'getTemplatePaths' => function () use ($twigConfig) {
                $paths = $twigConfig->getTemplatePaths();
                $paths[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%2$s/src/*/Zed/%1$s/Presentation';

                return $paths;
            },
        ]);

        return $twigBundleConfigMock;
    }

}
