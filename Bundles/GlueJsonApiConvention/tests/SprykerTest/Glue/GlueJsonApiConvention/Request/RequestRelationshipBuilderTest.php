<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestRelationshipBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
 * @group RequestRelationshipBuilderTest
 *
 * Add your own group annotations below this line
 */
class RequestRelationshipBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testRequestRelationshipBuilder(): void
    {
        //Arrange
        $expectedIncludes = ['resource1', 'resource2'];
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'include' => 'resource1,resource2',
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
        ]);

        //Act
        $requestRelationshipBuilder = new RequestRelationshipBuilder();
        $glueRequestTransfer = $requestRelationshipBuilder->extract($glueRequestTransfer);

        //Assert
        $this->assertSame($expectedIncludes[0], $glueRequestTransfer->getIncludedRelationships()[0]);
        $this->assertSame($expectedIncludes[1], $glueRequestTransfer->getIncludedRelationships()[1]);
    }

    /**
     * @return void
     */
    public function testRequestRelationshipWithoutInclude(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
        ]);

        //Act
        $requestRelationshipBuilder = new RequestRelationshipBuilder();
        $glueRequestTransfer = $requestRelationshipBuilder->extract($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueRequestTransfer->getIncludedRelationships());
    }

    /**
     * @return void
     */
    public function testRequestRelationshipWithEmptyInclude(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'include' => '',
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
        ]);

        //Act
        $requestRelationshipBuilder = new RequestRelationshipBuilder();
        $glueRequestTransfer = $requestRelationshipBuilder->extract($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueRequestTransfer->getIncludedRelationships());
    }
}
