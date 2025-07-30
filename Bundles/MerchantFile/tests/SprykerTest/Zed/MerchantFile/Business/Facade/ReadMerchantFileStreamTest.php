<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantFile\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantFileConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\MerchantFile\Business\Exception\MerchantFileNotFoundException;
use Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface;
use Spryker\Zed\MerchantFile\MerchantFileDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantFile
 * @group Business
 * @group Facade
 * @group ReadMerchantFileStreamTest
 * Add your own group annotations below this line
 */
class ReadMerchantFileStreamTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantFile\MerchantFileBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider provideFileStreamContent
     *
     * @param string $fileName
     * @param string $fileContent
     *
     * @return void
     */
    public function testReadsFileStream(string $fileName, string $fileContent): void
    {
        // Arrange
        $fileSystemServiceMock = $this->createFileSystemServiceMock($fileName, $fileContent);

        $this->tester->setDependency(
            MerchantFileDependencyProvider::SERVICE_FILE_SYSTEM,
            $fileSystemServiceMock,
        );

        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantFileTransfer = $this->tester->haveMerchantFile([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
        ]);

        $merchantFileConditionsTransfer = (new MerchantFileConditionsTransfer())
            ->addMerchantFileId($merchantFileTransfer->getIdMerchantFile())
            ->addUuid($merchantFileTransfer->getUuid())
            ->addType($merchantFileTransfer->getType());

        $merchantFileCriteriaTransfer = (new MerchantFileCriteriaTransfer())
            ->setMerchantFileConditions($merchantFileConditionsTransfer);

        // Act
        $fileStream = $this->tester->getFacade()->readMerchantFileStream($merchantFileCriteriaTransfer);

        // Assert
        $this->assertIsResource($fileStream, 'Expected a resource type for the file stream.');
        $this->assertIsNotClosedResource($fileStream);
        $this->assertSame($fileContent, stream_get_contents($fileStream), 'File stream content does not match expected content.');

        fclose($fileStream);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenMerchantFileNotFound(): void
    {
        // Arrange
        $this->expectException(MerchantFileNotFoundException::class);
        $this->expectExceptionMessage('Merchant file not found');

        $merchantFileConditionsTransfer = (new MerchantFileConditionsTransfer())
            ->addMerchantFileId(9999)
            ->addUuid('non-existing-uuid')
            ->addType('non-existing-type');

        $merchantFileCriteriaTransfer = (new MerchantFileCriteriaTransfer())
            ->setMerchantFileConditions($merchantFileConditionsTransfer);

        // Act
        $this->tester->getFacade()->readMerchantFileStream($merchantFileCriteriaTransfer);
    }

    /**
     * @return array
     */
    public static function provideFileStreamContent(): array
    {
        return [
            'text content' => ['file.txt', 'This is a sample text file content.'],
            'CSV content' => ['file.csv', "col_1,col_2,col_3\nvalue_1,value_2,value_3"],
            'JSON content' => ['file.json', '{"key1": "value1", "key2": "value2"}'],
        ];
    }

    /**
     * @param string $fileName
     * @param string $fileContent
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantFile\Dependency\Service\MerchantFileToFileSystemServiceInterface
     */
    protected function createFileSystemServiceMock(
        string $fileName,
        string $fileContent
    ): MockObject|MerchantFileToFileSystemServiceInterface {
        $fileSystemServiceMock = $this->createMock(MerchantFileToFileSystemServiceInterface::class);
        $filePath = codecept_output_dir() . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $fileSystemServiceMock
            ->expects($this->once())
            ->method('readStream')
            ->willReturnCallback(static function () use ($filePath, $fileContent) {
                $resource = fopen($filePath, 'w+');
                fwrite($resource, $fileContent);
                fseek($resource, 0);

                return $resource;
            });

        return $fileSystemServiceMock;
    }
}
