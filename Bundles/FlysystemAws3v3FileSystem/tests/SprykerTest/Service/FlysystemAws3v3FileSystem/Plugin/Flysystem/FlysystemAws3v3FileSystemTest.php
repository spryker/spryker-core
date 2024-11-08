<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem;

use Aws\S3\S3Client;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer;
use Generated\Shared\Transfer\FlysystemConfigLocalTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\FilesystemOperator;
use ReflectionClass;
use Spryker\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem\Aws3v3FilesystemBuilderPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Service\FlysystemAws3v3FileSystem\FlysystemAws3v3FileSystemBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group FlysystemAws3v3FileSystem
 * @group Plugin
 * @group Flysystem
 * @group FlysystemAws3v3FileSystemTest
 * Add your own group annotations below this line
 */
class FlysystemAws3v3FileSystemTest extends Unit
{
    /**
     * @var string
     */
    public const PATH_DOCUMENT = 'documents/';

    /**
     * @var \SprykerTest\Service\FlysystemAws3v3FileSystem\FlysystemAws3v3FileSystemBusinessTester
     */
    protected FlysystemAws3v3FileSystemBusinessTester $tester;

    /**
     * @dataProvider correctFlysystemConfigAws3v3DataProvider
     *
     * @param array<string, mixed> $flysystemConfigAws3v3Data
     *
     * @return void
     */
    public function testAws3v3FilesystemBuilderPluginReturnsFilesystemInstanceWithSuccessfulData(
        array $flysystemConfigAws3v3Data
    ): void {
        $this->validateIfPluginTestCanBeExecutedOrMarkTestSkipped();

        // Arrange
        $adapterConfigTransfer = $this->tester->haveFlysystemConfigAws3v3Transfer($flysystemConfigAws3v3Data);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        // Act
        $aws3v3Filesystem = (new Aws3v3FilesystemBuilderPlugin())->build($configTransfer);

        // Assert
        $this->assertInstanceOf(FilesystemOperator::class, $aws3v3Filesystem);
    }

    /**
     * @dataProvider streamReadsDataProvider
     *
     * @param bool $expectedStreamReads
     * @param bool|null $isStreamReads
     *
     * @return void
     */
    public function testAws3v3FilesystemBuilderPluginReturnsFilesystemInstanceWithCorrectlySetStreamReads(
        bool $expectedStreamReads,
        ?bool $isStreamReads
    ): void {
        $this->validateIfPluginTestCanBeExecutedOrMarkTestSkipped();

        // Arrange
        $adapterConfigTransfer = $this->tester->haveFlysystemConfigAws3v3Transfer([
            FlysystemConfigAws3v3Transfer::PATH => static::PATH_DOCUMENT,
            FlysystemConfigAws3v3Transfer::KEY => 'key',
            FlysystemConfigAws3v3Transfer::SECRET => 'secret',
            FlysystemConfigAws3v3Transfer::BUCKET => 'bucket',
            FlysystemConfigAws3v3Transfer::REGION => 'region',
            FlysystemConfigAws3v3Transfer::IS_STREAM_READS => $isStreamReads,
        ]);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        // Act
        $aws3v3Filesystem = (new Aws3v3FilesystemBuilderPlugin())->build($configTransfer);

        $filesystemReflectionClass = new ReflectionClass($aws3v3Filesystem);
        $filesystemAdapterReflectionProperty = $filesystemReflectionClass->getProperty('adapter');
        /** @var \League\Flysystem\AwsS3V3\AwsS3V3Adapter $adapter */
        $adapter = $filesystemAdapterReflectionProperty->getValue($aws3v3Filesystem);

        $adapterReflectionClass = new ReflectionClass($adapter);
        $adapterStreamReadsReflectionProperty = $adapterReflectionClass->getProperty('streamReads');

        // Assert
        $this->assertInstanceOf(FilesystemOperator::class, $aws3v3Filesystem);
        $this->assertSame($expectedStreamReads, $adapterStreamReadsReflectionProperty->getValue($adapter));
    }

    /**
     * @return void
     */
    public function testAws3v3FilesystemBuilderPluginReturnsFilesystemInstanceWithCorrectlySetEndpoint(): void
    {
        $this->validateIfPluginTestCanBeExecutedOrMarkTestSkipped();

        // Arrange
        $adapterConfigTransfer = $this->tester->haveFlysystemConfigAws3v3Transfer([
            FlysystemConfigAws3v3Transfer::PATH => static::PATH_DOCUMENT,
            FlysystemConfigAws3v3Transfer::ENDPOINT => 'http://localhost:9000',
            FlysystemConfigAws3v3Transfer::KEY => 'key',
            FlysystemConfigAws3v3Transfer::SECRET => 'secret',
            FlysystemConfigAws3v3Transfer::BUCKET => 'bucket',
            FlysystemConfigAws3v3Transfer::REGION => 'region',
            FlysystemConfigAws3v3Transfer::IS_STREAM_READS => true,
        ]);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        // Act
        $aws3v3Filesystem = (new Aws3v3FilesystemBuilderPlugin())->build($configTransfer);

        $filesystemReflectionClass = new ReflectionClass($aws3v3Filesystem);
        $filesystemAdapterReflectionProperty = $filesystemReflectionClass->getProperty('adapter');
        /** @var \League\Flysystem\AwsS3V3\AwsS3V3Adapter $adapter */
        $adapter = $filesystemAdapterReflectionProperty->getValue($aws3v3Filesystem);

        $adapterReflectionClass = new ReflectionClass($adapter);
        $adapterStreamReadsReflectionProperty = $adapterReflectionClass->getProperty('client');
        /** @var \Aws\S3\S3Client $s3Client */
        $s3Client = $adapterStreamReadsReflectionProperty->getValue($adapter);

        // Assert
        $this->assertInstanceOf(FilesystemOperator::class, $aws3v3Filesystem);
        $this->assertInstanceOf(S3Client::class, $s3Client);
        $this->assertSame($adapterConfigTransfer->getEndpoint(), (string)$s3Client->getEndpoint());
    }

    /**
     * @dataProvider incorrectFlysystemConfigAws3v3DataProvider
     *
     * @param array<string, mixed> $incorrectFlysystemConfigAws3v3Data
     *
     * @return void
     */
    public function testAws3v3FilesystemBuilderPluginThrowsExceptionWithIncorrectData(array $incorrectFlysystemConfigAws3v3Data): void
    {
        $this->validateIfPluginTestCanBeExecutedOrMarkTestSkipped();

        // Arrange
        $adapterConfigTransfer = $this->tester->haveFlysystemConfigAws3v3Transfer($incorrectFlysystemConfigAws3v3Data);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        (new Aws3v3FilesystemBuilderPlugin())->build($configTransfer);
    }

    /**
     * @return void
     */
    public function testAws3v3FilesystemBuilderPluginShouldAcceptType(): void
    {
        $Aws3v3FilesystemBuilderPlugin = new Aws3v3FilesystemBuilderPlugin();

        $adapterConfigTransfer = new FlysystemConfigLocalTransfer();
        $adapterConfigTransfer->setPath(static::PATH_DOCUMENT);

        $configTransfer = new FlysystemConfigTransfer();
        $configTransfer->setName('aws3v3');
        $configTransfer->setType(Aws3v3FilesystemBuilderPlugin::class);
        $configTransfer->setAdapterConfig($adapterConfigTransfer->modifiedToArray());

        $isTypeAccepted = $Aws3v3FilesystemBuilderPlugin->acceptType($configTransfer->getType());

        $this->assertTrue($isTypeAccepted);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function correctFlysystemConfigAws3v3DataProvider(): array
    {
        $correctAdapterConfig = [
            FlysystemConfigAws3v3Transfer::PATH => static::PATH_DOCUMENT,
            FlysystemConfigAws3v3Transfer::KEY => 'key',
            FlysystemConfigAws3v3Transfer::SECRET => 'secret',
            FlysystemConfigAws3v3Transfer::BUCKET => 'bucket',
            FlysystemConfigAws3v3Transfer::REGION => 'region',
            FlysystemConfigAws3v3Transfer::ENDPOINT => 'http://localhost:9000',
            FlysystemConfigAws3v3Transfer::ROOT => '/',
            FlysystemConfigAws3v3Transfer::VERSION => 'latest',
            FlysystemConfigAws3v3Transfer::IS_STREAM_READS => false,
        ];

        return [
            'correct adapter config' => [
                $correctAdapterConfig,
            ],
            'correct adapter config without stream reads' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::IS_STREAM_READS => null]),
            ],
            'correct adapter config without endpoint' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::ENDPOINT => null]),
            ],
            'correct adapter config without root' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::ROOT => null]),
            ],
            'correct adapter config without version' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::VERSION => null]),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function incorrectFlysystemConfigAws3v3DataProvider(): array
    {
        $correctAdapterConfig = [
            FlysystemConfigAws3v3Transfer::PATH => static::PATH_DOCUMENT,
            FlysystemConfigAws3v3Transfer::KEY => 'key',
            FlysystemConfigAws3v3Transfer::SECRET => 'secret',
            FlysystemConfigAws3v3Transfer::BUCKET => 'bucket',
            FlysystemConfigAws3v3Transfer::REGION => 'region',
        ];

        return [
            'empty adapter config' => [
                [],
            ],
            'empty path' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::PATH => null]),
            ],
            'empty key' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::KEY => null]),
            ],
            'empty secret' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::SECRET => null]),
            ],
            'empty bucket' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::BUCKET => null]),
            ],
            'empty region' => [
                array_merge($correctAdapterConfig, [FlysystemConfigAws3v3Transfer::REGION => null]),
            ],
        ];
    }

    /**
     * @return array<string, array<bool|null>>
     */
    public function streamReadsDataProvider(): array
    {
        return [
            'stream reads enabled' => [true, true],
            'stream reads disabled' => [false, false],
            'stream reads null' => [false, null],
        ];
    }

    /**
     * @return void
     */
    protected function validateIfPluginTestCanBeExecutedOrMarkTestSkipped(): void
    {
        if (!class_exists(S3Client::class) || !class_exists(AwsS3V3Adapter::class)) {
            $this->markTestSkipped(sprintf(
                'Requires %s and %s classes to be installed',
                S3Client::class,
                AwsS3V3Adapter::class,
            ));
        }
    }
}
