<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantFile\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use Spryker\Zed\MerchantFile\Business\MerchantFileBusinessFactory;
use Spryker\Zed\MerchantFile\Business\MerchantFileFacadeInterface;
use Spryker\Zed\MerchantFile\Dependency\Facade\MerchantFileToMerchantUserInterface;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;
use Spryker\Zed\MerchantFile\MerchantFileConfig;
use Spryker\Zed\MerchantFile\MerchantFileDependencyProvider;
use Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface;
use Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantFile
 * @group Business
 * @group Facade
 * @group WriteMerchantFileTest
 * Add your own group annotations below this line
 */
class WriteMerchantFileTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_FILE_NAME = 'FILE_NAME';

    /**
     * @var string
     */
    protected const KEY_FILESYSTEM_NAME = 'FILESYSTEM_NAME';

    /**
     * @var string
     */
    protected const KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP = 'FILE_TYPE_TO_CONTENT_TYPE_MAP';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_UNSUPPORTED_FILE_TYPE = 'File content type %contentType% unsupported for file type %fileType%.';

    /**
     * @var \SprykerTest\Zed\MerchantFile\MerchantFileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _tearDown(): void
    {
        parent::_tearDown();

        SpyMerchantFileQuery::create()->deleteAll();
    }

    /**
     * @dataProvider provideWriteMerchantFileData
     *
     * @param array<string, mixed> $input
     *
     * @return void
     */
    public function testWriteMerchantFileSuccessful(array $input): void
    {
        // Arrange
        $fileType = array_key_first($input[static::KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP]);
        $contentTypes = $input[static::KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP][$fileType];
        $contentType = $contentTypes[array_key_first($contentTypes)];
        $originalFileName = $input[static::KEY_FILE_NAME];

        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $config = $this->createConfigMock(
            $input[static::KEY_FILESYSTEM_NAME],
            $input[static::KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP],
        );
        $facade = $this->prepareFacade($config);
        $merchantUserFacadeMock = $this->createMock(MerchantFileToMerchantUserInterface::class);
        $merchantUserFacadeMock
            ->method('getCurrentMerchantUser')
            ->willReturn($merchantUserTransfer);

        $this->tester->setDependency(MerchantFileDependencyProvider::FACADE_MERCHANT_USER, $merchantUserFacadeMock);

        $this->tester->setDependency(
            MerchantFileDependencyProvider::PLUGINS_MERCHANT_FILE_VALIDATION,
            [$this->createMerchantFileValidationPluginMock()],
        );

        $this->tester->setDependency(
            MerchantFileDependencyProvider::PLUGINS_MERCHANT_FILE_POST_SAVE,
            [$this->createMerchantFilePostSavePluginMock()],
        );

        $this->tester->setDependency(
            MerchantFileDependencyProvider::SERVICE_FILE_SYSTEM,
            $this->createFileSystemServiceMock(),
        );

        $merchantFileTransfer = $this->tester->buildMerchantFileTransfer([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $userTransfer->getIdUser(),
            MerchantFileTransfer::TYPE => $fileType,
            MerchantFileTransfer::CONTENT_TYPE => $contentType,
            MerchantFileTransfer::ORIGINAL_FILE_NAME => $originalFileName,
        ]);

        // Act
        $merchantFileResultTransfer = $facade->writeMerchantFile($merchantFileTransfer);

        // Assert
        $this->assertTrue($merchantFileResultTransfer->getIsSuccessful());
        $this->assertNotNull($merchantFileResultTransfer->getMerchantFile());
        $this->assertNotNull($merchantFileResultTransfer->getMerchantFile()->getIdMerchantFile());
        $this->assertNotNull($merchantFileResultTransfer->getMerchantFile()->getUuid());
    }

    /**
     * @return void
     */
    public function testWriteMerchantFileFailsForUnsupportedFileContentType(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();

        $config = $this->createConfigMock('merchant-file', []);
        $facade = $this->prepareFacade($config);

        $merchantFileTransfer = $this->tester->buildMerchantFileTransfer([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $userTransfer->getIdUser(),
        ]);

        // Act
        $merchantFileResultTransfer = $facade->writeMerchantFile($merchantFileTransfer);

        // Assert
        $this->assertFalse($merchantFileResultTransfer->getIsSuccessful());
        $this->assertCount(1, $merchantFileResultTransfer->getMessages());
        $this->assertEquals(static::ERROR_MESSAGE_UNSUPPORTED_FILE_TYPE, $merchantFileResultTransfer->getMessages()->offsetGet(0)->getValue());
    }

    /**
     * @return array
     */
    public static function provideWriteMerchantFileData(): array
    {
        return [
            'one content type' => [
                [
                    static::KEY_FILESYSTEM_NAME => 'merchant-file-1',
                    static::KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP => ['data-import' => ['application/csv']],
                    static::KEY_FILE_NAME => 'merchant-file-1.csv',
                ],
            ],
            'multiple content types' => [
                [
                    static::KEY_FILESYSTEM_NAME => 'merchant-file-2',
                    static::KEY_FILE_TYPE_TO_CONTENT_TYPE_MAP => [
                        'document' => ['application/pdf', 'application/msword'],
                        'data-import' => ['application/csv', 'text/csv'],
                    ],
                    static::KEY_FILE_NAME => 'merchant-file-2.pdf',
                ],
            ],
        ];
    }

    /**
     * @param string $fileSystemName
     * @param array<string, array<string>> $fileTypeToContentTypeMapping
     *
     * @return \PHPUnit\Framework\MockObject\Stub|\Spryker\Zed\MerchantFile\MerchantFileConfig
     */
    protected function createConfigMock(
        string $fileSystemName,
        array $fileTypeToContentTypeMapping
    ): Stub|MerchantFileConfig {
        $configStub = $this->createStub(MerchantFileConfig::class);

        $configStub
            ->method('getFileSystemName')
            ->willReturn($fileSystemName);

        $configStub
            ->method('getFileTypeToContentTypeMapping')
            ->willReturn($fileTypeToContentTypeMapping);

        return $configStub;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantFile\MerchantFileConfig $config
     *
     * @return \Spryker\Zed\MerchantFile\Business\MerchantFileFacadeInterface
     */
    protected function prepareFacade(MockObject|MerchantFileConfig $config): MerchantFileFacadeInterface
    {
        $facade = $this->tester->getFacade();
        $factory = new MerchantFileBusinessFactory();

        $factory->setConfig($config);
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFileValidationPluginInterface
     */
    protected function createMerchantFileValidationPluginMock(): MockObject|MerchantFileValidationPluginInterface
    {
        $merchantFileValidationPluginMock = $this->createMock(MerchantFileValidationPluginInterface::class);

        $merchantFileValidationPluginMock
            ->expects($this->once())
            ->method('validate')
            ->willReturnCallback(static fn (MerchantFileTransfer $merchantFileTransfer, MerchantFileResultTransfer $merchantFileResultTransfer): MerchantFileResultTransfer => $merchantFileResultTransfer);

        return $merchantFileValidationPluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantFileExtension\Dependency\Plugin\MerchantFilePostSavePluginInterface
     */
    protected function createMerchantFilePostSavePluginMock(): MockObject|MerchantFilePostSavePluginInterface
    {
        $merchantFilePostSavePluginMock = $this->createMock(MerchantFilePostSavePluginInterface::class);

        $merchantFilePostSavePluginMock
            ->expects($this->once())
            ->method('execute')
            ->willReturnCallback(static fn (MerchantFileTransfer $merchantFileTransfer): MerchantFileTransfer => $merchantFileTransfer);

        return $merchantFilePostSavePluginMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface
     */
    protected function createFileSystemServiceMock(): MockObject|MerchantFileToFileSystemServiceInterface
    {
        $fileSystemServiceMock = $this->createMock(MerchantFileToFileSystemServiceInterface::class);
        $fileSystemServiceMock
            ->expects($this->once())
            ->method('write');

        return $fileSystemServiceMock;
    }
}
