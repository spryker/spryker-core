<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueApplication\FormatRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group FormatRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class FormatRequestBuilderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormatRequestBuilderPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();

        //Act
        $formatRequestBuilderPlugin = new FormatRequestBuilderPlugin();
        $glueRequestTransfer = $formatRequestBuilderPlugin->build($glueRequestTransfer);

        //Assert
        $this->assertEquals($this->tester::CONTENT_TYPE, $glueRequestTransfer->getRequestedFormat());
        $this->assertEmpty($glueRequestTransfer->getAcceptedFormat());
    }
}
