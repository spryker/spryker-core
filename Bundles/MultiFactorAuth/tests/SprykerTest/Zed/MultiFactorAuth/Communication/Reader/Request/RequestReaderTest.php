<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\MultiFactorAuth\Communication\Reader\Request;

use Codeception\Test\Unit;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReader;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MultiFactorAuth
 * @group Communication
 * @group Reader
 * @group Request
 * @group RequestReaderTest
 * Add your own group annotations below this line
 */
class RequestReaderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_VALUE = 'test-value';

    /**
     * @var string
     */
    protected const TEST_PARAMETER = 'test-parameter';

    /**
     * @var string
     */
    protected const FORM_NAME = 'form';

    /**
     * @var \Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReader
     */
    protected RequestReader $requestReader;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requestReader = new RequestReader();
    }

    /**
     * @return void
     */
    public function testGetReturnsValueFromQuery(): void
    {
        // Arrange
        $request = new Request([static::TEST_PARAMETER => static::TEST_VALUE]);

        // Act
        $value = $this->requestReader->get($request, static::TEST_PARAMETER);

        // Assert
        $this->assertSame(static::TEST_VALUE, $value);
    }

    /**
     * @return void
     */
    public function testGetReturnsValueFromRequest(): void
    {
        // Arrange
        $request = new Request([], [static::TEST_PARAMETER => static::TEST_VALUE]);

        // Act
        $value = $this->requestReader->get($request, static::TEST_PARAMETER);

        // Assert
        $this->assertSame(static::TEST_VALUE, $value);
    }

    /**
     * @return void
     */
    public function testGetReturnsValueFromFormData(): void
    {
        // Arrange
        $formData = [static::TEST_PARAMETER => static::TEST_VALUE];
        $request = new Request([], [static::FORM_NAME => $formData]);

        // Act
        $value = $this->requestReader->get($request, static::TEST_PARAMETER, static::FORM_NAME);

        // Assert
        $this->assertSame(static::TEST_VALUE, $value);
    }

    /**
     * @return void
     */
    public function testGetReturnsNullWhenParameterNotFound(): void
    {
        // Arrange
        $request = new Request();

        // Act
        $value = $this->requestReader->get($request, static::TEST_PARAMETER);

        // Assert
        $this->assertNull($value);
    }
}
