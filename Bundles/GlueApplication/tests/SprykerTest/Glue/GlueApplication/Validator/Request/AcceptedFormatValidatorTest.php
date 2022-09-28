<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Validator\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Encoder\Response\JsonResponseEncoderStrategy;
use Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface;
use Spryker\Glue\GlueApplication\GlueApplicationFactory;
use Spryker\Glue\GlueApplication\Validator\Request\AcceptedFormatValidator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Validator
 * @group Request
 * @group AcceptedFormatValidatorTest
 * Add your own group annotations below this line
 */
class AcceptedFormatValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const ALLOWED_HEADER = 'allowed-header';

    /**
     * @return void
     */
    public function testEmptyAcceptedFormatWillReturnBadRequest(): void
    {
        //Act
        $acceptedFormatValidator = new AcceptedFormatValidator([$this->createJsonResponseEncoderPlugin()]);
        $glueRequestValidationTransfer = $acceptedFormatValidator->validate(new GlueRequestTransfer());

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $glueRequestValidationTransfer->getStatus());
        $this->assertGreaterThan(0, $glueRequestValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testWrongAcceptedFormatWillReturnBadRequest(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setAcceptedFormat('json');

        //Act
        $acceptedFormatValidator = new AcceptedFormatValidator([$this->createJsonResponseEncoderPlugin()]);
        $glueRequestValidationTransfer = $acceptedFormatValidator->validate($glueRequestTransfer);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, $glueRequestValidationTransfer->getStatus());
        $this->assertGreaterThan(0, $glueRequestValidationTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testCorrectAcceptedFormatWillReturnValidRequest(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setAcceptedFormat('application/json');

        //Act
        $acceptedFormatValidator = new AcceptedFormatValidator([$this->createJsonResponseEncoderPlugin()]);
        $glueRequestValidationTransfer = $acceptedFormatValidator->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
        $this->assertEquals(0, $glueRequestValidationTransfer->getErrors()->count());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface
     */
    protected function createJsonResponseEncoderPlugin(): ResponseEncoderStrategyInterface
    {
        $factory = $this->createFactory();

        return new JsonResponseEncoderStrategy($factory->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\GlueApplicationFactory
     */
    protected function createFactory(): GlueApplicationFactory
    {
        return new GlueApplicationFactory();
    }
}
