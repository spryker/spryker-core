<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest\Client;

use Codeception\Test\Unit;
use Spryker\Client\ZedRequest\Plugin\AcceptEncodingHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\Plugin\AuthTokenHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\Plugin\RequestIdHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\ZedRequestDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group HttpClientTest
 * Add your own group annotations below this line
 */
class HttpClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ZedRequest\ZedRequestClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcceptEncodingHeaderIsSetWhenAcceptEncodingHeaderExpanderPluginIsUsed(): void
    {
        // Arrange
        $this->tester->setDependency(ZedRequestDependencyProvider::PLUGINS_HEADER_EXPANDER, [
            new AcceptEncodingHeaderExpanderPlugin(),
        ]);
        $httpClient = $this->tester->getFactory()->createHttpClient();

        // Act
        $header = $httpClient->getHeaders();

        // Assert
        $this->assertArrayHasKey('Accept-Encoding', $header);
    }

    /**
     * @return void
     */
    public function testAuthTokenHeaderIsSetWhenAuthTokenHeaderExpanderPluginIsUsed(): void
    {
        // Arrange
        $this->tester->setDependency(ZedRequestDependencyProvider::PLUGINS_HEADER_EXPANDER, [
            new AuthTokenHeaderExpanderPlugin(),
        ]);
        $httpClient = $this->tester->getFactory()->createHttpClient();

        // Act
        $header = $httpClient->getHeaders();

        // Assert
        $this->assertArrayHasKey('Auth-Token', $header);
    }

    /**
     * @return void
     */
    public function testRequestIdHeaderIsSetWhenRequestIdHeaderExpanderPluginIsUsed(): void
    {
        // Arrange
        $this->tester->setDependency(ZedRequestDependencyProvider::PLUGINS_HEADER_EXPANDER, [
            new RequestIdHeaderExpanderPlugin(),
        ]);
        $httpClient = $this->tester->getFactory()->createHttpClient();

        // Act
        $header = $httpClient->getHeaders();

        // Assert
        $this->assertArrayHasKey('Auth-Token', $header);
    }

    /**
     * @return void
     */
    public function testBackwardCompatibilityAuthTokenHeaderIsSetWithoutHeaderExpanderPlugin(): void
    {
        // Arrange
        $httpClient = $this->tester->getFactory()->createHttpClient();

        // Act
        $header = $httpClient->getHeaders();

        // Assert
        $this->assertArrayHasKey('Auth-Token', $header);
    }

    /**
     * @return void
     */
    public function testBackwardCompatibilityRequestIdHeaderIsSetWithoutHeaderExpanderPlugin(): void
    {
        // Arrange
        $httpClient = $this->tester->getFactory()->createHttpClient();

        // Act
        $header = $httpClient->getHeaders();

        // Assert
        $this->assertArrayHasKey('X-Request-ID', $header);
    }
}
