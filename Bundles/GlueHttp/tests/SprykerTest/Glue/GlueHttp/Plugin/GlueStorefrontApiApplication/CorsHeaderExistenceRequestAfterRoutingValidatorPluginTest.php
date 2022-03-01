<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueHttp\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueHttp\Plugin\GlueStorefrontApiApplication\CorsHeaderExistenceRequestAfterRoutingValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueHttp
 * @group Plugin
 * @group GlueStorefrontApiApplication
 * @group CorsHeaderExistenceRequestAfterRoutingValidatorPluginTest
 * Add your own group annotations below this line
 */
class CorsHeaderExistenceRequestAfterRoutingValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueHttp\GlueHttpTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCorsHeaderExistenceRequestAfterRoutingValidatorPlugin(): void
    {
        //Arrange
        $glueRequestTransfer = $this->tester->createGlueRequestTransfer();
        $resourceMock = $this->createMock(ResourceInterface::class);

        //Act
        $corsHeaderExistenceRequestAfterRoutingValidatorPlugin = new CorsHeaderExistenceRequestAfterRoutingValidatorPlugin();
        $glueRequestValidationTransfer = $corsHeaderExistenceRequestAfterRoutingValidatorPlugin->validateRequest($glueRequestTransfer, $resourceMock);

        // Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
        $this->assertEmpty($glueRequestValidationTransfer->getValidationError());
    }
}
