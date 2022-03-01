<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\AcceptFormatRequestValidatorPlugin;
use Spryker\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention\JsonResponseEncoderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group AcceptFormatRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class AcceptFormatRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptFormatRequestValidatorPlugin(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueRestApiConventionDependencyProvider::PLUGINS_RESPONSE_ENCODER,
            [
                new JsonResponseEncoderPlugin(),
            ],
        );
        $glueRequestTransfer = (new GlueRequestTransfer())->setAcceptedFormat('application/json');

        //Act
        $acceptFormatRequestValidatorPlugin = new AcceptFormatRequestValidatorPlugin();
        $glueRequestValidationTransfer = $acceptFormatRequestValidatorPlugin->validate($glueRequestTransfer);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
        $this->assertEquals(0, $glueRequestValidationTransfer->getErrors()->count());
    }
}
