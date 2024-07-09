<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Plugin\Log;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\User\Communication\Plugin\Log\CurrentUserDataRequestProcessorPlugin;
use Spryker\Zed\User\UserDependencyProvider;
use SprykerTest\Zed\User\UserCommunicationTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Communication
 * @group Plugin
 * @group Log
 * @group CurrentRequestProcessorPluginTest
 * Add your own group annotations below this line
 */
class CurrentRequestProcessorPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\User\Communication\Processor\CurrentUserDataRequestLogProcessor::SESSION_KEY_USER
     *
     * @var string
     */
    protected const SESSION_KEY_USER = 'user:currentUser';

    /**
     * @var \SprykerTest\Zed\User\UserCommunicationTester
     */
    protected UserCommunicationTester $tester;

    /**
     * @return void
     */
    public function testInvokeDoesNotModifyDataIfSessionIsNotSet(): void
    {
        // Arrange
        $data = ['extra' => [], 'context' => []];
        $currentUserDataRequestProcessorPlugin = new CurrentUserDataRequestProcessorPlugin();
        $this->setRequestStackService($this->createRequestStack());

        // Act
        $processedData = $currentUserDataRequestProcessorPlugin->__invoke($data);

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
        $currentUserDataRequestProcessorPlugin = new CurrentUserDataRequestProcessorPlugin();
        $requestStack = $this->createRequestStack();
        $requestStack->getCurrentRequest()->setSession(new Session(new MockArraySessionStorage()));
        $this->setRequestStackService($requestStack);

        // Act
        $processedData = $currentUserDataRequestProcessorPlugin->__invoke($data);

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
        $currentUserDataRequestProcessorPlugin = new CurrentUserDataRequestProcessorPlugin();
        $requestStack = $this->createRequestStack();
        $session = new Session(new MockArraySessionStorage());
        $userTransfer = (new UserTransfer())->setUsername('test_username')->setUuid('test_uuid');
        $session->set(static::SESSION_KEY_USER, $userTransfer);
        $requestStack->getCurrentRequest()->setSession($session);
        $this->setRequestStackService($requestStack);

        // Act
        $processedData = $currentUserDataRequestProcessorPlugin->__invoke($data);

        // Arrange
        $this->assertArrayHasKey('username', $processedData['extra']['request']);
        $this->assertArrayHasKey('user_uuid', $processedData['extra']['request']);
        $this->assertSame('test_username', $processedData['extra']['request']['username']);
        $this->assertSame('test_uuid', $processedData['extra']['request']['user_uuid']);
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
        $this->tester->setDependency(UserDependencyProvider::SERVICE_REQUEST_STACK, $requestStack);
    }
}
