<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueHttp\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueHttp\Request\CorsHeaderExistenceValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group Request
 * @group CorsHeaderExistenceValidatorTest
 *
 * Add your own group annotations below this line
 */
class CorsHeaderExistenceValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const HTTP_METHOD = 'OPTIONS';

    /**
     * @return void
     */
    public function testCorsHeadersExistence(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setMeta([
            'access-control-request-method' => ['GET, POST'],
            'access-control-request-headers' => ['Content-Type'],
            'origin' => ['origin'],
            ])
            ->setMethod(static::HTTP_METHOD);

        //Act
        $corsHeaderExistenceValidator = new CorsHeaderExistenceValidator();
        $glueRequestValidationTransfer = $corsHeaderExistenceValidator->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
        $this->assertEmpty($glueRequestValidationTransfer->getValidationError());
    }

    /**
     * @return void
     */
    public function testCorsHeaderNonExist(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setMeta([
                'access-control-request-method' => 'POST',
                'access-control-request-headers' => ['Content-Type'],
            ])
            ->setMethod(static::HTTP_METHOD);

        //Act
        $corsHeaderExistenceValidator = new CorsHeaderExistenceValidator();
        $glueRequestValidationTransfer = $corsHeaderExistenceValidator->validate($glueRequestTransfer);

        // Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame('One or more of the required headers (access-control-request-method, access-control-request-headers, origin) for the options method are missing.', $glueRequestValidationTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testNotOptionMethod(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setMeta(['access-control-request-method' => ['GET, POST']])
            ->setMethod('GET');

        //Act
        $corsHeaderExistenceValidator = new CorsHeaderExistenceValidator();
        $glueRequestValidationTransfer = $corsHeaderExistenceValidator->validate($glueRequestTransfer);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
        $this->assertEmpty($glueRequestValidationTransfer->getValidationError());
    }
}
