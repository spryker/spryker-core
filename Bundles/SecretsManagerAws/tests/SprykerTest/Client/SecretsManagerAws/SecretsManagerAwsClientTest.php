<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SecretsManagerAws;

use Aws\Exception\AwsException;
use Aws\Result;
use Aws\SecretsManager\SecretsManagerClient;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\SecretKeyTransfer;
use Generated\Shared\Transfer\SecretTagTransfer;
use Generated\Shared\Transfer\SecretTransfer;
use PHPUnit\Framework\Constraint\Constraint;
use Psr\Log\LoggerInterface;
use Spryker\Client\SecretsManagerAws\Adapter\SecretsManagerAwsAdapter;
use Spryker\Client\SecretsManagerAws\SecretsManagerAwsDependencyProvider;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SecretsManagerAws
 * @group SecretsManagerAwsClientTest
 * Add your own group annotations below this line
 */
class SecretsManagerAwsClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SECRET_VALUE = 'secret-value';

    /**
     * @var string
     */
    protected const TEST_SECRET_TAG_KEY_1 = 'secret-tag-key-1';

    /**
     * @var string
     */
    protected const TEST_SECRET_TAG_VALUE_1 = 'secret-tag-value-1';

    /**
     * @var string
     */
    protected const TEST_SECRET_TAG_KEY_2 = 'secret-tag-key-2';

    /**
     * @var string
     */
    protected const TEST_SECRET_TAG_VALUE_2 = 'secret-tag-value-2';

    /**
     * @var \SprykerTest\Client\SecretsManagerAws\SecretsManagerAwsClientTester
     */
    protected $tester;

    /**
     * @dataProvider getSecretTransferCorrectSeedDataForCreateSecretDataProvider
     *
     * @param array<string, mixed> $secretTransferSeedData
     * @param \PHPUnit\Framework\Constraint\Constraint $requestConstraint
     *
     * @return void
     */
    public function testCreateSecretSavesSecretValueWithSuccessfulResponse(
        array $secretTransferSeedData,
        Constraint $requestConstraint
    ): void {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer($secretTransferSeedData);
        $secretsManagerClientMock = $this->getSecretsManagerClientMock();

        // Assert
        $secretsManagerClientMock->expects($this->once())
            ->method('createSecret')
            ->with($requestConstraint)
            ->willReturn(new Result([]));

        // Act
        $isSuccessful = $this->tester->getClient()->createSecret($secretTransfer);
        $this->assertTrue($isSuccessful);
    }

    /**
     * @return void
     */
    public function testCreateSecretLogsErrorWithExceptionalResponse(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer();
        $secretsManagerClientMock = $this->getSecretsManagerClientMock();
        $loggerMock = $this->createMock(LoggerInterface::class);
        $this->mockSecretsManagerAwsAdapterWithLogger($loggerMock);

        // Assert
        $loggerMock->expects($this->once())
            ->method('error');
        $secretsManagerClientMock->expects($this->once())
            ->method('createSecret')
            ->willThrowException($this->createMock(AwsException::class));

        // Act
        $isSuccessful = $this->tester->getClient()->createSecret($secretTransfer);
        $this->assertFalse($isSuccessful);
    }

    /**
     * @dataProvider getSecretTransferInvalidSeedDataForCreateSecretDataProvider
     *
     * @param array<string, mixed> $secretTransferSeedData
     *
     * @return void
     */
    public function testCreateSecretThrowsExceptionWhenPassingInvalidData(array $secretTransferSeedData): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransferWithoutSecretKey($secretTransferSeedData);
        $this->getSecretsManagerClientMock();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getClient()->createSecret($secretTransfer);
    }

    /**
     * @return void
     */
    public function testGetSecretReturnsSecretValueWithSuccessfulResponse(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer([SecretTransfer::VALUE => null]);
        $secretsManagerClientMock = $this->getSecretsManagerClientMock();
        $secretValue = static::TEST_SECRET_VALUE;

        // Assert
        $secretsManagerClientMock->expects($this->once())
            ->method('getSecretValue')
            ->with($this->arrayHasKey('SecretId'))
            ->willReturn(new Result(['SecretString' => $secretValue]));

        // Act
        $secretTransfer = $this->tester->getClient()->getSecret($secretTransfer);
        $this->assertSame($secretValue, $secretTransfer->getValue());
    }

    /**
     * @return void
     */
    public function testGetSecretReturnsNoSecretValueAndLogsErrorWithExceptionalResponse(): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransfer([SecretTransfer::VALUE => null]);
        $secretsManagerClientMock = $this->getSecretsManagerClientMock();
        $loggerMock = $this->createMock(LoggerInterface::class);
        $this->mockSecretsManagerAwsAdapterWithLogger($loggerMock);

        // Assert
        $loggerMock->expects($this->once())
            ->method('error');
        $secretsManagerClientMock->expects($this->once())
            ->method('getSecretValue')
            ->willThrowException($this->createMock(AwsException::class));

        // Act
        $secretTransfer = $this->tester->getClient()->getSecret($secretTransfer);
        $this->assertNull($secretTransfer->getValue());
    }

    /**
     * @dataProvider getSecretTransferInvalidSeedDataForGetSecretDataProvider
     *
     * @param array<string, mixed> $secretTransferSeedData
     *
     * @return void
     */
    public function testGetSecretThrowsExceptionWhenPassingInvalidData(array $secretTransferSeedData): void
    {
        // Arrange
        $secretTransfer = $this->tester->buildSecretTransferWithoutSecretKey($secretTransferSeedData);
        $this->getSecretsManagerClientMock();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getClient()->getSecret($secretTransfer);
    }

    /**
     * @return array<string, <string, mixed>>
     */
    public function getSecretTransferCorrectSeedDataForCreateSecretDataProvider(): array
    {
        return [
            'secret without tags' => [
                'secretTransferSeedData' => [
                    SecretTransfer::VALUE => static::TEST_SECRET_VALUE,
                    SecretTransfer::SECRET_TAGS => [],
                ],
                'requestConstraint' => static::logicalAnd(
                    static::arrayHasKey('Name'),
                    static::arrayHasKey('SecretString'),
                    static::logicalNot(static::arrayHasKey('Tags')),
                    static::callback(function ($value) {
                        /** @var array<string, mixed> $value */
                        return $value['SecretString'] === static::TEST_SECRET_VALUE;
                    }),
                ),
            ],
            'secret with tags' => [
                'secretTransferSeedData' => [
                    SecretTransfer::VALUE => static::TEST_SECRET_VALUE,
                    SecretTransfer::SECRET_TAGS => [
                        [
                            SecretTagTransfer::KEY => static::TEST_SECRET_TAG_KEY_1,
                            SecretTagTransfer::VALUE => static::TEST_SECRET_TAG_VALUE_1,
                        ],
                        [
                            SecretTagTransfer::KEY => static::TEST_SECRET_TAG_KEY_2,
                            SecretTagTransfer::VALUE => static::TEST_SECRET_TAG_VALUE_2,
                        ],
                    ],
                ],
                'requestConstraint' => static::logicalAnd(
                    static::arrayHasKey('Name'),
                    static::arrayHasKey('SecretString'),
                    static::arrayHasKey('Tags'),
                    static::callback(function ($value) {
                        /** @var array<string, mixed> $value */
                        return count($value['Tags']) === 2
                            && $value['Tags'][0]['Key'] === static::TEST_SECRET_TAG_KEY_1
                            && $value['Tags'][0]['Value'] === static::TEST_SECRET_TAG_VALUE_1
                            && $value['Tags'][1]['Key'] === static::TEST_SECRET_TAG_KEY_2
                            && $value['Tags'][1]['Value'] === static::TEST_SECRET_TAG_VALUE_2;
                    }),
                ),
            ],
        ];
    }

    /**
     * @return array<string, <string, mixed>>
     */
    public function getSecretTransferInvalidSeedDataForCreateSecretDataProvider(): array
    {
        return [
            'missing secret value' => [
                'secretTransferSeedData' => [
                    SecretTransfer::VALUE => null,
                ],
            ],
        ] + $this->getSecretTransferInvalidSeedDataForGetSecretDataProvider();
    }

    /**
     * @return array<string, <string, mixed>>
     */
    public function getSecretTransferInvalidSeedDataForGetSecretDataProvider(): array
    {
        return [
            'missing secret key' => [
                'secretTransferSeedData' => [
                    SecretTransfer::SECRET_KEY => null,
                ],
            ],
            'missing secret key identifier' => [
                'secretTransferSeedData' => [
                    SecretTransfer::SECRET_KEY => [
                        SecretKeyTransfer::IDENTIFIER => null,
                    ],
                ],
            ],
            'missing secret key prefix' => [
                'secretTransferSeedData' => [
                    SecretTransfer::SECRET_KEY => [
                        SecretKeyTransfer::PREFIX => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Aws\SecretsManager\SecretsManagerClient
     */
    protected function getSecretsManagerClientMock(): SecretsManagerClient
    {
        $secretsManagerClientMock = $this->getMockBuilder(SecretsManagerClient::class)
            ->disableOriginalConstructor()
            ->addMethods(['createSecret', 'getSecretValue'])
            ->getMock();
        $this->tester->setDependency(
            SecretsManagerAwsDependencyProvider::CLIENT_SECRETS_MANAGER_AWS,
            $secretsManagerClientMock,
        );

        return $secretsManagerClientMock;
    }

    /**
     * @param \Psr\Log\LoggerInterface $loggerMock
     *
     * @return void
     */
    protected function mockSecretsManagerAwsAdapterWithLogger(LoggerInterface $loggerMock): void
    {
        $secretsManagerAwsFactory = $this->tester->getFactory();
        $secretsManagerAwsAdapterMock = $this->getMockBuilder(SecretsManagerAwsAdapter::class)
            ->onlyMethods(['getLogger'])
            ->setConstructorArgs([
                $secretsManagerAwsFactory->getSecretsManagerAwsClient(),
                $secretsManagerAwsFactory->createSecretGenerator(),
            ])
            ->getMock();
        $secretsManagerAwsAdapterMock->method('getLogger')->willReturn($loggerMock);
        $this->tester->mockFactoryMethod('createSecretsManagerAwsAdapter', $secretsManagerAwsAdapterMock);
    }
}
