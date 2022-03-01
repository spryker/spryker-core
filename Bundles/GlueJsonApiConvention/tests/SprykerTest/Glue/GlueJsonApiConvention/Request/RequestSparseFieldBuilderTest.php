<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestSparseFieldBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
 * @group RequestSparseFieldBuilderTest
 *
 * Add your own group annotations below this line
 */
class RequestSparseFieldBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testSparseFields(): void
    {
        //Arrange
        $expectedResourceType = 'items';
        $expectedFields = ['att1', 'att2', 'att3'];
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'fields' => [
                'items' => 'att1,att2,att3',
            ],
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
        ]);

        //Act
        $requestSparseFieldBuilder = new RequestSparseFieldBuilder();
        $glueRequestTransfer = $requestSparseFieldBuilder->extract($glueRequestTransfer);

        //Assert
        $this->assertNotEmpty($glueRequestTransfer->getSparseResources()->getArrayCopy());
        $this->assertSame($expectedResourceType, $glueRequestTransfer->getSparseResources()->getArrayCopy()[0]->getResourceType());
        $this->assertSame($expectedFields[2], $glueRequestTransfer->getSparseResources()->getArrayCopy()[0]->getFields()[2]);
    }

    /**
     * @return void
     */
    public function testSparseWithoutFields(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setQueryFields([
            'page' => [
                'limit' => 1,
                'offset' => 10,
            ],
        ]);

        //Act
        $requestSparseFieldBuilder = new RequestSparseFieldBuilder();
        $glueRequestTransfer = $requestSparseFieldBuilder->extract($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueRequestTransfer->getSparseResources()->getArrayCopy());
    }
}
