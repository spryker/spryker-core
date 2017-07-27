<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Symfony\Form\RequestTokenProvider;

use Codeception\Test\Unit;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Symfony
 * @group Form
 * @group RequestTokenProvider
 * @group SessionStorageTest
 */
class SessionStorageTest extends Unit
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $sessionMock;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected $sessionStorage;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->sessionMock = $this->getMockBuilder(SessionInterface::class)->getMock();
        $this->sessionStorage = new SessionStorage($this->sessionMock);
    }

    /**
     * @return void
     */
    public function testSessionStorageGetToken()
    {
        $testKey = 'test_key';
        $testValue = 'test_value';

        $this->sessionMock->expects($this->once())
            ->method('get')
            ->with(SessionStorage::SESSION_KEY_PREFIX . $testKey)
            ->willReturn($testValue);

        $this->assertEquals($testValue, $this->sessionStorage->getToken($testKey));
    }

    /**
     * @return void
     */
    public function testSessionStorageSetToken()
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
    public function testSessionStorageDeleteToken()
    {
        $testKey = 'test_key';

        $this->sessionMock->expects($this->once())
            ->method('remove')
            ->with(SessionStorage::SESSION_KEY_PREFIX . $testKey);

        $this->sessionStorage->deleteToken($testKey);
    }

}
