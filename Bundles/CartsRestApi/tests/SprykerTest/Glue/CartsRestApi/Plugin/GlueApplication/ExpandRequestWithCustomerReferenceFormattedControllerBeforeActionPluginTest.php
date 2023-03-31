<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface;
use Spryker\Glue\CartsRestApi\Plugin\GlueApplication\ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPluginTest
 * Add your own group annotations below this line
 */
class ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\Processor\Expander\RequestExpander::REQUEST_KEY_CUSTOMER_REFERENCE
     *
     * @var string
     */
    protected const REQUEST_KEY_CUSTOMER_REFERENCE = 'customerReference';

    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'testCustomerReference';

    /**
     * @var string
     */
    protected const TEST_HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID = 'testHeader';

    /**
     * /**
     *
     * @var \SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester
     */
    protected CartRestApiGlueTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(
            static::SERVICE_RESOURCE_BUILDER,
            new RestResourceBuilder(),
        );
    }

    /**
     * @return void
     */
    public function testBeforeActionShouldNotExpandRequestWhenCustomerReferenceIsAlreadySet(): void
    {
        // Arrange
        $request = new Request([], [
            static::REQUEST_KEY_CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
        ]);
        $request->headers->set(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID, static::TEST_HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);

        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin = new ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin();

        // Act
        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin->beforeAction($request);

        // Assert
        $this->assertSame(static::TEST_CUSTOMER_REFERENCE, $request->request->get(static::REQUEST_KEY_CUSTOMER_REFERENCE));
    }

    /**
     * @return void
     */
    public function testBeforeActionShouldNotExpandRequestWhenAnonymousCustomerUniqueIdHeaderIsNotProvided(): void
    {
        // Arrange
        $request = new Request();
        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin = new ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin();

        // Act
        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin->beforeAction($request);

        // Assert
        $this->assertNull($request->request->get(static::REQUEST_KEY_CUSTOMER_REFERENCE));
    }

    /**
     * @return void
     */
    public function testBeforeActionShouldExpandRequestWithCustomerReference(): void
    {
        // Arrange
        $request = new Request();
        $request->headers->set(CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID, static::TEST_HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID);

        $this->tester->setDependency(
            CartsRestApiDependencyProvider::CLIENT_PERSISTENT_CART,
            $this->createPersistentCartClientMock(
                static::TEST_HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
                static::TEST_CUSTOMER_REFERENCE,
            ),
        );

        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin = new ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin();

        // Act
        $expandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin->beforeAction($request);

        // Assert
        $this->assertSame(static::TEST_CUSTOMER_REFERENCE, $request->request->get(static::REQUEST_KEY_CUSTOMER_REFERENCE));
    }

    /**
     * @param string $anonymousCustomerUniqueId
     * @param string $expectedCustomerReference
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToPersistentCartClientInterface
     */
    protected function createPersistentCartClientMock(
        string $anonymousCustomerUniqueId,
        string $expectedCustomerReference
    ): CartsRestApiToPersistentCartClientInterface {
        $persistentCartClientMock = $this->getMockBuilder(CartsRestApiToPersistentCartClientInterface::class)
            ->getMock();

        $persistentCartClientMock->method('generateGuestCartCustomerReference')
            ->with($anonymousCustomerUniqueId)
            ->willReturn($expectedCustomerReference);

        return $persistentCartClientMock;
    }
}
