<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\User\RestUserValidator;
use Spryker\Glue\GlueApplication\Rest\User\RestUserValidatorInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group RestUserValidatorTest
 *
 * Add your own group annotations below this line
 */
class RestUserValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateWhenPluginSucceedsShouldReturnNull(): void
    {
        // Arrange
        $restUserValidatorPluginMock = $this->createRestUserValidatorPluginMock();
        $restUserValidatorPluginMock
            ->method('validate')
            ->willReturn(null);
        $restUserValidator = $this->createRestUserValidator([$restUserValidatorPluginMock]);
        $restRequest = (new RestRequest())->createRestRequest();

        // Act
        $restErrorCollectionTransfer = $restUserValidator->validate($restRequest);

        // Assert
        $this->assertNull($restErrorCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWhenPluginFailsShouldReturnError(): void
    {
        // Arrange
        $restUserValidatorPluginMock = $this->createRestUserValidatorPluginMock();
        $restUserValidatorPluginMock
            ->method('validate')
            ->willReturn(new RestErrorMessageTransfer());
        $restUserValidator = $this->createRestUserValidator([$restUserValidatorPluginMock]);
        $restRequest = (new RestRequest())->createRestRequest();

        // Act
        $restErrorCollectionTransfer = $restUserValidator->validate($restRequest);

        // Assert
        $this->assertNotEmpty($restErrorCollectionTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRestUserValidatorPluginMock(): RestUserValidatorPluginInterface
    {
        return $this->getMockBuilder(RestUserValidatorPluginInterface::class)
           ->onlyMethods(['validate'])
           ->getMock();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface[] $restUserValidatorPlugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\User\RestUserValidatorInterface
     */
    protected function createRestUserValidator(array $restUserValidatorPlugins = []): RestUserValidatorInterface
    {
        return new RestUserValidator($restUserValidatorPlugins);
    }
}
