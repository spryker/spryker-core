<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\SparseFieldRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group SparseFieldRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class SparseFieldRequestBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSparseFieldRequestBuilderPlugin(): void
    {
        //Arrange
        $expectedResourceType = 'items';
        $expectedFields = ['att1', 'att2', 'att3'];
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $sparseFieldRequestBuilderPlugin = new SparseFieldRequestBuilderPlugin();
        $glueRequestTransfer = $sparseFieldRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $this->assertNotEmpty($glueRequestTransfer->getSparseResources()->getArrayCopy());
        $this->assertSame($expectedResourceType, $glueRequestTransfer->getSparseResources()->getArrayCopy()[0]->getResourceType());
        $this->assertSame($expectedFields[2], $glueRequestTransfer->getSparseResources()->getArrayCopy()[0]->getFields()[2]);
    }
}
