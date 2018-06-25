<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
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
    public function testValidatePostRequestShouldReturnErorWhenDataMissing(): void
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
        $restRequestValidatorPluginMock = $this->createRestRequestValidatorPluginMock();

        $restRequestValidatorPluginMock
            ->method('validate')
            ->willReturn(new RestErrorMessageTransfer());

        $restRequestValidator = $this->createRestRequestValidator([$restRequestValidatorPluginMock]);

        $request = Request::create('/', Request::METHOD_GET);
        $restRequest = (new RestRequest())->createRestRequest();

        $restErrorMessageTransfer = $restRequestValidator->validate($request, $restRequest);

        $this->assertNotEmpty($restErrorMessageTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface
     */
    protected function createRestRequestValidatorPluginMock(): ValidateRestRequestPluginInterface
    {
        return $this->getMockBuilder(ValidateRestRequestPluginInterface::class)
           ->setMethods(['validate'])
           ->getMock();
    }

    /**
     * @param array $plugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    protected function createRestRequestValidator(array $plugins = []): RestRequestValidatorInterface
    {
        return new RestRequestValidator($plugins);
    }
}
