<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilNetwork\Model\Request;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Service\UtilNetwork\Model\Request\RequestId;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilNetwork
 * @group Model
 * @group Request
 * @group RequestIdTest
 * Add your own group annotations below this line
 */
class RequestIdTest extends Unit
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->resetRequestIdHandler();
    }

    /**
     * @return void
     */
    public function testGetRequestIdShouldReturnSameRequestId(): void
    {
        $this->assertSame(
            (new RequestId())->getRequestId(),
            (new RequestId())->getRequestId()
        );
    }

    /**
     * @return void
     */
    public function testGetRequestIdShouldReturnGivenRequestId(): void
    {
        $_SERVER[RequestId::REQUEST_ID_HEADER_KEY] = 'requestId';

        $this->assertSame(
            'requestId',
            (new RequestId())->getRequestId()
        );
    }

    /**
     * @return void
     */
    protected function resetRequestIdHandler(): void
    {
        $reflectionClass = new ReflectionClass(RequestId::class);
        $reflectionProperty = $reflectionClass->getProperty('requestId');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null);
    }
}
