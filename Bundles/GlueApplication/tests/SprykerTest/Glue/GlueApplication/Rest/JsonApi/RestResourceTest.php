<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\JsonApi;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group JsonApi
 * @group RestResourceTest
 *
 * Add your own group annotations below this line
 */
class RestResourceTest extends Unit
{
    /**
     * @return void
     */
    public function testToArrayShouldReturnResourceAsArray(): void
    {
        $restTestAttributesTransfer = new RestTestAttributesTransfer();
        $restResource = new RestResource('tests', 1, $restTestAttributesTransfer);

        $restResource->addLink('test', 'resources/123');

        $restResourceRel = new RestResource('related', 1, $restTestAttributesTransfer);
        $restResource->addRelationship($restResourceRel);

        $array = $restResource->toArray(true);

        $this->assertArrayHasKey(RestResourceInterface::RESOURCE_ATTRIBUTES, $array);
        $this->assertArrayHasKey(RestResourceInterface::RESOURCE_ID, $array);
        $this->assertArrayHasKey(RestResourceInterface::RESOURCE_RELATIONSHIPS, $array);
        $this->assertArrayHasKey(RestResourceInterface::RESOURCE_TYPE, $array);
        $this->assertArrayHasKey(RestResourceInterface::RESOURCE_LINKS, $array);

        $this->assertCount(2, $array[RestResourceInterface::RESOURCE_ATTRIBUTES]);
        $this->assertCount(1, $array[RestResourceInterface::RESOURCE_LINKS]);
        $this->assertEquals(1, $array[RestResourceInterface::RESOURCE_ID]);
        $this->assertEquals('tests', $array[RestResourceInterface::RESOURCE_TYPE]);

        $this->assertArrayHasKey('related', $array[RestResourceInterface::RESOURCE_RELATIONSHIPS]);

        $this->assertCount(1, $array[RestResourceInterface::RESOURCE_RELATIONSHIPS]['related']['data']);
    }
}
