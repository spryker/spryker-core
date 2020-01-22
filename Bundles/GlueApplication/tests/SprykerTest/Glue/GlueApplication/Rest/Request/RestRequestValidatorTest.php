<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Request
 * @group RestRequestValidatorTest
 *
 * Add your own group annotations below this line
 */
class RestRequestValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testValidatePostRequestShouldReturnErrorWhenDataMissing(): void
    {
        $restRequestValidator = $this->createRestRequestValidator();

        $request = Request::create('/', Request::METHOD_POST);
        $restRequest = (new RestRequest())->createRestRequest(Request::METHOD_POST);

        $restErrorMessageTransfer = $restRequestValidator->validate($request, $restRequest);

        $this->assertNotEmpty($restErrorMessageTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWhenPluginFailsShouldReturnError(): void
    {
        $restRequestValidatorPluginMock = $this->createValidateRestRequestPluginMock();

        $restRequestValidatorPluginMock
            ->method('validate')
            ->willReturn(new RestErrorMessageTransfer());

        $restRequestValidator = $this->createRestRequestValidator([$restRequestValidatorPluginMock]);

        $request = Request::create('/', Request::METHOD_GET);
        $restRequest = (new RestRequest())->createRestRequest();

        $restErrorCollectionTransfer = $restRequestValidator->validate($request, $restRequest);

        $this->assertNotEmpty($restErrorCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testValidateWhenValidatorPluginFailsShouldReturnError(): void
    {
        $restRequestValidatorPluginMock = $this->createRestRequestValidatorPluginMock();

        $restRequestValidatorPluginMock
            ->method('validate')
            ->willReturn(new RestErrorCollectionTransfer());

        $restRequestValidator = $this->createRestRequestValidator([], [$restRequestValidatorPluginMock]);

        $request = Request::create('/', Request::METHOD_GET);
        $restRequest = (new RestRequest())->createRestRequest();

        $restErrorCollectionTransfer = $restRequestValidator->validate($request, $restRequest);

        $this->assertNotEmpty($restErrorCollectionTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createValidateRestRequestPluginMock(): ValidateRestRequestPluginInterface
    {
        return $this->getMockBuilder(ValidateRestRequestPluginInterface::class)
           ->setMethods(['validate'])
           ->getMock();
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRestRequestValidatorPluginMock(): RestRequestValidatorPluginInterface
    {
        return $this->getMockBuilder(RestRequestValidatorPluginInterface::class)
           ->setMethods(['validate'])
           ->getMock();
    }

    /**
     * @param array $plugins
     * @param array $restRequestValidatorPlugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    protected function createRestRequestValidator(array $plugins = [], array $restRequestValidatorPlugins = []): RestRequestValidatorInterface
    {
        return new RestRequestValidator($plugins, $restRequestValidatorPlugins);
    }
}
