<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Resource\MissingResource;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Resource
 * @group MissingResourceTest
 * Add your own group annotations below this line
 */
class MissingResourceTest extends Unit
{
    /**
     * @return void
     */
    public function testGetResourceResponse(): void
    {
        $expectedErrorCode = 404;
        $expectedErrorMessage = 'error message';
        $missingResource = new MissingResource('007', $expectedErrorMessage);
        $glueRequestTransfer = new GlueRequestTransfer();

        $this->assertIsCallable($missingResource->getResource($glueRequestTransfer));
        $result = call_user_func($missingResource->getResource($glueRequestTransfer));
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame((int)$expectedErrorCode, $result->getHttpStatus());
        $this->assertSame('007', $result->getErrors()->getArrayCopy()[0]->getCode());
        $this->assertSame($expectedErrorMessage, $result->getErrors()->getArrayCopy()[0]->getMessage());
    }
}
