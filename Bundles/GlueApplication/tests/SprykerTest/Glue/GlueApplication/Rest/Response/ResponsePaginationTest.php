<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponsePagination;
use Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use SprykerTest\Glue\GlueApplication\Stub\RestResponse;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group Response
 * @group ResponsePaginationTest
 *
 * Add your own group annotations below this line
 */
class ResponsePaginationTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildPaginationLinksShouldReturnPaginationLinks(): void
    {
        $responsePagination = $this->createResponsePagination();

        $restResponse = (new RestResponse())->createRestResponse();
        $restRequest = (new RestRequest())->createRestRequest();

        $pagination = $responsePagination->buildPaginationLinks($restResponse, $restRequest);

        $this->assertArrayHasKey(RestLinkInterface::LINK_FIRST, $pagination);
        $this->assertArrayHasKey(RestLinkInterface::LINK_LAST, $pagination);
        $this->assertArrayHasKey(RestLinkInterface::LINK_NEXT, $pagination);
        $this->assertArrayHasKey(RestLinkInterface::LINK_PREV, $pagination);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    public function createResponsePagination(): ResponsePaginationInterface
    {
        return new ResponsePagination('');
    }
}
