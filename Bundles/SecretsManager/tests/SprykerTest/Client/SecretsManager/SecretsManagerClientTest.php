<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecretsManager;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\SecretsManager\Exception\MissingSecretsManagerProviderPluginException;
use Spryker\Client\SecretsManager\SecretsManagerDependencyProvider;
use Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecretsManager
 * @group SecretsManagerClientTest
 * Add your own group annotations below this line
 */
class SecretsManagerClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SecretsManager\SecretsManagerClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateSecretCallsCorrespondingPluginMethodWithCorrectDataIfPluginProvided(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer();
        $secretsManagerProviderPluginMock = $this->getSecretsManagerProviderPluginMock();
        $createSecretResponse = true;

        // Assert
        $secretsManagerProviderPluginMock->expects($this->once())
            ->method('createSecret')
            ->with($secretTransfer)
            ->willReturn($createSecretResponse);

        // Act
        $isSuccessful = $this->tester->getClient()->createSecret($secretTransfer);

        // Assert
        $this->assertSame($createSecretResponse, $isSuccessful);
    }

    /**
     * @return void
     */
    public function testCreateSecretThrowsExceptionIfNoPluginProvided(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer();

        // Assert
        $this->expectException(MissingSecretsManagerProviderPluginException::class);

        // Act
        $this->tester->getClient()->createSecret($secretTransfer);
    }

    /**
     * @return void
     */
    public function testGetSecretCallsCorrespondingPluginMethodWithCorrectDataIfPluginProvided(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer();
        $secretsManagerProviderPluginMock = $this->getSecretsManagerProviderPluginMock();
        $getSecretResponse = $this->tester->buildSecretTransfer([
            SecretTransfer::VALUE => 'secret value',
        ]);

        // Assert
        $secretsManagerProviderPluginMock->expects($this->once())
            ->method('getSecret')
            ->with($secretTransfer)
            ->willReturn($getSecretResponse);

        // Act
        $receivedSecretTransfer = $this->tester->getClient()->getSecret($secretTransfer);

        // Assert
        $this->assertSame($getSecretResponse, $receivedSecretTransfer);
    }

    /**
     * @return void
     */
    public function testGetSecretThrowsExceptionIfNoPluginProvided(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer();

        // Assert
        $this->expectException(MissingSecretsManagerProviderPluginException::class);

        // Act
        $this->tester->getClient()->getSecret($secretTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SecretsManagerExtension\Dependency\Plugin\SecretsManagerProviderPluginInterface
     */
    protected function getSecretsManagerProviderPluginMock(): SecretsManagerProviderPluginInterface
    {
        $secretsManagerProviderPluginMock = $this->createMock(SecretsManagerProviderPluginInterface::class);
        $this->tester->setDependency(
            SecretsManagerDependencyProvider::PLUGIN_SECRETS_MANAGER_PROVIDER,
            $secretsManagerProviderPluginMock,
        );

        return $secretsManagerProviderPluginMock;
    }
}
