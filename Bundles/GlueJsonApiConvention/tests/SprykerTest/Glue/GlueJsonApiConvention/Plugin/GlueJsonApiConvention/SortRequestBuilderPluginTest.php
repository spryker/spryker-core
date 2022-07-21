<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\SortRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group SortRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class SortRequestBuilderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FIRST_FIELD_NAME = 'field1';

    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSortRequestBuilderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $sortRequestBuilderPlugin = new SortRequestBuilderPlugin();
        $glueRequestTransfer = $sortRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $sorting = $glueRequestTransfer->getSortings();
        $this->assertCount(2, $glueRequestTransfer->getSortings());
        $firstSorting = $glueRequestTransfer->getSortings()->offsetGet(0);
        $this->assertInstanceOf(SortTransfer::class, $firstSorting);
        $this->assertSame(static::FIRST_FIELD_NAME, $firstSorting->getField());
    }
}
