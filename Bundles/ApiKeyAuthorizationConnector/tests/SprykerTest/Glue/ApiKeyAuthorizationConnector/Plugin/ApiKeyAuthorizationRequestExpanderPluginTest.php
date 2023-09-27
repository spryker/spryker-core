<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ApiKeyAuthorizationConnector\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\ApiKeyAuthorizationConnector\Plugin\GlueBackendApiApplicationAuthorizationConnector\ApiKeyAuthorizationRequestExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ApiKeyAuthorizationConnector
 * @group Plugin
 * @group ApiKeyAuthorizationRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class ApiKeyAuthorizationRequestExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ApiKeyAuthorizationConnector\ApiKeyAuthorizationConnectorTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->createFakeApiKeyRecord();
        $this->tester->createFakeExpiredApiKeyRecord();
        $this->tester->createFakeNoExpiredApiKeyRecord();
    }

    /**
     * @return void
     */
    public function testIdentifierIsSetWhenPresentInRequestHeader(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestWithApiKeyHeader();
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNotNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return void
     */
    public function testIdentifierIsSetWhenPresentInRequestQueryParam(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestWithApiKeyQueryParam($this->tester::FOO_KEY);
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNotNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return void
     */
    public function testIdentifierNotSetWhenWrongInRequest(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestWithWrongApiKey();
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return void
     */
    public function testIdentifierNotSetWhenNotInRequest(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestTransfer();
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return void
     */
    public function testExpandWithExpiredKeyWillReturnEmptyIdentifier(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestWithApiKeyQueryParam($this->tester::EXPIRED_API_KEY);
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return void
     */
    public function testExpandWithNotExpiredKeyWillReturnIdentifier(): void
    {
        //Arrange
        $authorizationRequestTransfer = $this->tester->getAuthorizationRequestTransferWithIdentity();
        $glueRequestTransfer = $this->tester->getGlueRequestWithApiKeyQueryParam($this->tester::NOT_EXPIRED_API_KEY);
        $apiKeyRequestExpanderPlugin = $this->getApiKeyAuthorizationRequestExpanderPlugin();

        //Act
        $authorizationRequestTransfer = $apiKeyRequestExpanderPlugin->expand($authorizationRequestTransfer, $glueRequestTransfer);

        //Assert
        $this->assertNotNull($authorizationRequestTransfer->getIdentityOrFail()->getApiKeyIdentifier());
    }

    /**
     * @return \Spryker\Glue\ApiKeyAuthorizationConnector\Plugin\GlueBackendApiApplicationAuthorizationConnector\ApiKeyAuthorizationRequestExpanderPlugin
     */
    protected function getApiKeyAuthorizationRequestExpanderPlugin(): ApiKeyAuthorizationRequestExpanderPlugin
    {
        return new ApiKeyAuthorizationRequestExpanderPlugin();
    }
}
