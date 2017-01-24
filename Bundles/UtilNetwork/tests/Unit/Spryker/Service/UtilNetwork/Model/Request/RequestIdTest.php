<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\UtilNetwork\Request;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Spryker\Service\UtilNetwork\Model\Request\RequestId;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Application
 * @group Log
 * @group Request
 * @group RequestIdTest
 */
class RequestIdTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->resetRequestIdHandler();
    }

    /**
     * @return void
     */
    public function testGetRequestIdShouldReturnSameRequestId()
    {
        $this->assertSame(
            (new RequestId())->getRequestId(),
            (new RequestId())->getRequestId()
        );
    }

    /**
     * @return void
     */
    public function testGetRequestIdShouldReturnGivenRequestId()
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
    protected function resetRequestIdHandler()
    {
        $reflectionClass = new ReflectionClass(RequestId::class);
        $reflectionProperty = $reflectionClass->getProperty('requestId');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null);
    }

}
