<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\ContentType;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolver;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group ContentType
 * @group ContentTypeResolverTest
 *
 * Add your own group annotations below this line
 */
class ContentTypeResolverTest extends Unit
{
    /**
     * @var string
     */
    protected $contentType = 'application/vnd.api+json; version=1.1';

    /**
     * @return void
     */
    public function testMatchContentTypeShouldReturnContentTypeParts(): void
    {
        $contentTypeResolver = $this->createContentTypeResolver();

        $contentTypeParts = $contentTypeResolver->matchContentType($this->contentType);

        $this->assertSame('json', $contentTypeParts[1]);
        $this->assertSame('1.1', $contentTypeParts[2]);
    }

    /**
     * @return void
     */
    public function testAddResponseHeaderShouldAddJsonApiContentType(): void
    {
        $contentTypeResolver = $this->createContentTypeResolver();

        $restRequest = (new RestRequest())->createRestRequest();

        $httpResponse = new Response();
        $contentTypeResolver->addResponseHeaders($restRequest, $httpResponse);

        $contentType = $httpResponse->headers->get('Content-Type');

        $this->assertSame($this->contentType, $contentType);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    protected function createContentTypeResolver(): ContentTypeResolverInterface
    {
        return new ContentTypeResolver();
    }
}
