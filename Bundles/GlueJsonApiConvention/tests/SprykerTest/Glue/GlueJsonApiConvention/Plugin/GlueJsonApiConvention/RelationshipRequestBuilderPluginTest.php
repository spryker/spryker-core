<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\RelationshipRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueJsonApiConvention
 * @group RelationshipRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class RelationshipRequestBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRelationshipRequestBuilderPlugin(): void
    {
        //Arrange
        $expectedIncludes = ['resource1', 'resource2'];
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $relationshipRequestBuilderPlugin = new RelationshipRequestBuilderPlugin();
        $glueRequestTransfer = $relationshipRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $this->assertSame($expectedIncludes[0], $glueRequestTransfer->getIncludedRelationships()[0]);
        $this->assertSame($expectedIncludes[1], $glueRequestTransfer->getIncludedRelationships()[1]);
    }
}
