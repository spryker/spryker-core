<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseHeaders;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use SprykerTest\Glue\GlueApplication\Stub\RestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Response
 * @group ResponseHeadersTest
 *
 * Add your own group annotations below this line
 */
class ResponseHeadersTest extends Unit
{
    /**
     * @return void
     */
    public function testAddHeadersShouldAddBaseHeaders(): void
    {
        $formatResponseHeaderPlugin = $this->createFormatResponseHeadersPluginMock();

        $formatResponseHeaderPlugin->method('format')
            ->willReturnCallback(
                function (
                    Response $httpResponse,
                    RestResponseInterface $restResponse,
                    RestRequestInterface $restRequest
                ) {
                    $httpResponse->headers->set('x-custom', 'custom');

                    return $httpResponse;
                }
            );

        $responseHeaders = $this->createResponseHeaders([
            $formatResponseHeaderPlugin,
        ]);

        $restResponse = (new RestResponse())->createRestResponse();
        $restRequest = (new RestRequest())->createRestRequest();

        $response = $responseHeaders->addHeaders(new Response(), $restResponse, $restRequest);

        $contentLanguage = $response->headers->get('Content-Language');
        $this->assertEquals('DE', $contentLanguage);

        $xCustom = $response->headers->get('x-custom');

        $this->assertEquals('custom', $xCustom);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[] $plugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface
     */
    protected function createResponseHeaders(array $plugins): ResponseHeadersInterface
    {
        return new ResponseHeaders($plugins, $this->createContentTypeMock(), new GlueApplicationConfig());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected function createContentTypeMock(): ContentTypeResolverInterface
    {
        return $this->getMockBuilder(ContentTypeResolverInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface
     */
    protected function createFormatResponseHeadersPluginMock(): FormatResponseHeadersPluginInterface
    {
        return $this->getMockBuilder(FormatResponseHeadersPluginInterface::class)
            ->setMethods(['format'])
            ->getMock();
    }
}
