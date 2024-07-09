<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Customer\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Customer\CustomerDependencyProvider;
use Spryker\Yves\Customer\Plugin\Log\CurrentCustomerDataRequestProcessorPlugin;
use SprykerTest\Yves\Customer\CustomerTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Customer
 * @group Plugin
 * @group Log
 * @group CurrentRequestProcessorPluginTest
 * Add your own group annotations below this line
 */
class CurrentRequestProcessorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Yves\Customer\Processor\CurrentCustomerDataRequestLogProcessor::SESSION_KEY_CUSTOMER_DATA
     *
     * @var string
     */
    protected const SESSION_KEY_CUSTOMER_DATA = 'customer data';

    /**
     * @var \SprykerTest\Yves\Customer\CustomerTester
     */
    protected CustomerTester $tester;

    /**
     * @return void
     */
    public function testInvokeDoesNotModifyDataIfSessionIsNotSet(): void
    {
        // Arrange
        $data = ['extra' => [], 'context' => []];
        $currentCustomerDataRequestProcessorPlugin = new CurrentCustomerDataRequestProcessorPlugin();
        $this->setRequestStackService($this->createRequestStack());

        // Act
        $processedData = $currentCustomerDataRequestProcessorPlugin->__invoke($data);

        // Arrange
        $this->assertSame($data, $processedData);
    }

    /**
     * @return void
     */
    public function testInvokeDoesNotModifyDataIfUserIsNotSetInSession(): void
    {
        // Arrange
        $data = ['extra' => [], 'context' => []];
        $currentCustomerDataRequestProcessorPlugin = new CurrentCustomerDataRequestProcessorPlugin();
        $requestStack = $this->createRequestStack();
        $requestStack->getCurrentRequest()->setSession(new Session(new MockArraySessionStorage()));
        $this->setRequestStackService($requestStack);

        // Act
        $processedData = $currentCustomerDataRequestProcessorPlugin->__invoke($data);

        // Arrange
        $this->assertSame($data, $processedData);
    }

    /**
     * @return void
     */
    public function testInvokeSetsUserData(): void
    {
        // Arrange
        $data = ['extra' => [], 'context' => []];
        $currentCustomerDataRequestProcessorPlugin = new CurrentCustomerDataRequestProcessorPlugin();
        $requestStack = $this->createRequestStack();
        $session = new Session(new MockArraySessionStorage());

        $customerTransfer = (new CustomerTransfer())->setEmail('test_email')->setCustomerReference('test_reference');
        $session->set(static::SESSION_KEY_CUSTOMER_DATA, $customerTransfer);
        $requestStack->getCurrentRequest()->setSession($session);
        $this->setRequestStackService($requestStack);

        // Act
        $processedData = $currentCustomerDataRequestProcessorPlugin->__invoke($data);

        // Arrange
        $this->assertArrayHasKey('username', $processedData['extra']['request']);
        $this->assertArrayHasKey('customer_reference', $processedData['extra']['request']);
        $this->assertSame('test_email', $processedData['extra']['request']['username']);
        $this->assertSame('test_reference', $processedData['extra']['request']['customer_reference']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function createRequestStack(): RequestStack
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        return $requestStack;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     *
     * @return void
     */
    protected function setRequestStackService(RequestStack $requestStack): void
    {
        $this->tester->setDependency(CustomerDependencyProvider::SERVICE_REQUEST_STACK, $requestStack);
    }
}
