<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\PaginationRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group PaginationRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class PaginationRequestBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPaginationRequestBuilderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $paginationRequestBuilderPlugin = new PaginationRequestBuilderPlugin();
        $glueRequestTransfer = $paginationRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $paginationTransfer = $glueRequestTransfer->getPagination();
        $this->assertInstanceOf(PaginationTransfer::class, $paginationTransfer);
        $this->assertSame(10, $paginationTransfer->getOffset());
        $this->assertSame(1, $paginationTransfer->getLimit());
    }
}
