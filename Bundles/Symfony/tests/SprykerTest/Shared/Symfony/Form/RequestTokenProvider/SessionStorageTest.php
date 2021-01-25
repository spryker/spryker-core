<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Symfony\Form\RequestTokenProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Symfony
 * @group Form
 * @group RequestTokenProvider
 * @group SessionStorageTest
 * Add your own group annotations below this line
 */
class SessionStorageTest extends Unit
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionMock;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected $sessionStorage;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->getMockBuilder(SessionInterface::class)->getMock();
        $this->sessionStorage = new SessionStorage($this->sessionMock);
    }

    /**
     * @return void
     */
    public function testSessionStorageGetToken(): void
    {
        $testKey = 'test_key';
        $testValue = 'test_value';

        $this->sessionMock->expects($this->once())
            ->method('get')
            ->with(SessionStorage::SESSION_KEY_PREFIX . $testKey)
            ->willReturn($testValue);

        $this->assertSame($testValue, $this->sessionStorage->getToken($testKey));
    }

    /**
     * @return void
     */
    public function testSessionStorageSetToken(): void
    {
        $testKey = 'test_key';
        $testValue = 'test_value';

        $this->sessionMock->expects($this->once())
            ->method('set')
            ->with(SessionStorage::SESSION_KEY_PREFIX . $testKey, $testValue);

        $this->sessionStorage->setToken($testKey, $testValue);
    }

    /**
     * @return void
     */
    public function testSessionStorageDeleteToken(): void
    {
        $testKey = 'test_key';

        $this->sessionMock->expects($this->once())
            ->method('remove')
            ->with(SessionStorage::SESSION_KEY_PREFIX . $testKey);

        $this->sessionStorage->deleteToken($testKey);
    }
}
