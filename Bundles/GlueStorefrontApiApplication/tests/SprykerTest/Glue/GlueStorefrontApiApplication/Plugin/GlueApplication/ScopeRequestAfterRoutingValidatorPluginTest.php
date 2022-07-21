<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestCustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\ScopeRequestAfterRoutingValidatorPlugin;
use SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Plugin
 * @group GlueApplication
 * @group ScopeRequestAfterRoutingValidatorPluginTest
 * Add your own group annotations below this line
 */
class ScopeRequestAfterRoutingValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateRequestWillReturnValidResponseForResourceWitchNotImplementScopeDefinitionPluginInterface(): void
    {
        // Arrange
        $resourcePluginMockMock = $this->createMock(ResourceInterface::class);
        $scopeRequestAfterRoutingValidatorPlugin = new ScopeRequestAfterRoutingValidatorPlugin();

        //Act
        $glueRequestValidationTransfer = $scopeRequestAfterRoutingValidatorPlugin->validate(
            new GlueRequestTransfer(),
            $resourcePluginMockMock,
        );

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateRequestWillReturnNotValidGlueRequestValidationTransfer(): void
    {
        // Arrange
        $scopeRequestAfterRoutingValidatorPlugin = new ScopeRequestAfterRoutingValidatorPlugin();

        //Act
        $glueRequestValidationTransfer = $scopeRequestAfterRoutingValidatorPlugin->validate(
            new GlueRequestTransfer(),
            new TestResource(),
        );

        //Assert
        $this->assertSame(Response::HTTP_FORBIDDEN, $glueRequestValidationTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testValidateRequestWillReturnValidGlueRequestValidationTransfer(): void
    {
        // Arrange
        $scopeRequestAfterRoutingValidatorPlugin = new ScopeRequestAfterRoutingValidatorPlugin();

        //Act
        $glueRequestValidationTransfer = $scopeRequestAfterRoutingValidatorPlugin->validate(
            $this->createGlueRequestTransfer(),
            new TestResource(),
        );

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return (new GlueRequestTransfer())->setRequestCustomer(
            (new GlueRequestCustomerTransfer())->setScopes([$this->tester::GET_METHOD_SCOPE]),
        )->setMethod($this->tester::GET_METHOD_NAME);
    }
}
