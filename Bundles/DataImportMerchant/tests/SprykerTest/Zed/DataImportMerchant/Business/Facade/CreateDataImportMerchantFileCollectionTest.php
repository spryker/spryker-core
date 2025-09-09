<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImportMerchant\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DataImportMerchantFileCollectionRequestBuilder;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionRequestTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface;
use SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImportMerchant
 * @group Business
 * @group Facade
 * @group CreateDataImportMerchantFileCollectionTest
 * Add your own group annotations below this line
 */
class CreateDataImportMerchantFileCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\ImporterTypeValidatorRule::GLOSSARY_KEY_VALIDATION_IMPORTER_TYPE_NOT_SUPPORTED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_IMPORTER_TYPE_NOT_SUPPORTED = 'data_import_merchant.validation.importer_type_not_supported';

    /**
     * @uses \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\UserExistsValidatorRule::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND = 'data_import_merchant.validation.user_not_found';

    /**
     * @uses \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\MerchantExistsValidatorRule::GLOSSARY_KEY_VALIDATION_MERCHANT_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_NOT_FOUND = 'data_import_merchant.validation.merchant_not_found';

    /**
     * @uses \Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile\FileContentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_INVALID_FILE_CONTENT_TYPE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_INVALID_FILE_CONTENT_TYPE = 'data_import_merchant.validation.invalid_file_content_type';

    /**
     * @var \SprykerTest\Zed\DataImportMerchant\DataImportMerchantBusinessTester
     */
    protected DataImportMerchantBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockConfigMethod('getSupportedImporterTypes', ['merchant-product']);
        $this->tester->mockConfigMethod('getSupportedContentTypes', ['text/csv', 'application/csv', 'text/plain']);
        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::SERVICE_FILE_SYSTEM,
            $this->createFileSystemServiceMock(),
        );
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionReturnsSuccessfulResult(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($dataImportMerchantFileCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles());

        $createdDataImportMerchantFileTransfer = $dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles()->getIterator()->current();
        $this->assertSame('pending', $createdDataImportMerchantFileTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionReturnsErrorWhenUnsupportedImporterType(): void
    {
        // Arrange
        $invalidDataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $invalidDataImportMerchantFileTransfer->setImporterType('invalid-importer-type');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_IMPORTER_TYPE_NOT_SUPPORTED, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionReturnsErrorWhenUserNotFound(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $dataImportMerchantFileTransfer->setIdUser(99999);

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionReturnsErrorWhenMerchantNotFound(): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $dataImportMerchantFileTransfer->setMerchantReference('non-existing-merchant-reference');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('0', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_MERCHANT_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionReturnsErrorWhenInvalidFileContentType(): void
    {
        // Arrange
        $invalidDataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $invalidDataImportMerchantFileTransfer->getFileInfoOrFail()->setContentType('application/pdf');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $dataImportMerchantFileCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_INVALID_FILE_CONTENT_TYPE, $errorTransfer->getMessageOrFail());
    }

    /**
     * @return void
     */
    public function testShouldExecuteDataImportMerchantFileValidatorPluginStack(): void
    {
        // Assert
        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_VALIDATOR,
            [
                $this->getDataImportMerchantFileValidatorPluginMock(),
            ],
        );

        // Arrange
        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->setIsTransactional(true);

        // Act
        $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldExecuteDataImportMerchantFileRequestExpanderPluginStack(): void
    {
        // Assert
        $this->tester->setDependency(
            DataImportMerchantDependencyProvider::PLUGINS_DATA_IMPORT_MERCHANT_FILE_REQUEST_EXPANDER,
            [
                $this->getDataImportMerchantFileRequestExpanderPluginMock(),
            ],
        );

        // Arrange
        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->setIsTransactional(true);

        // Act
        $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @dataProvider getRequiredFieldsDataProvider
     *
     * @param string $fieldName
     *
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionThrowsExceptionWhenRequiredFieldIsMissing(string $fieldName): void
    {
        // Arrange
        $dataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();

        if (str_contains($fieldName, '.')) {
            $fieldParts = explode('.', $fieldName);
            $nestedObject = $dataImportMerchantFileTransfer->offsetGet($fieldParts[0]);
            $nestedObject->offsetSet($fieldParts[1], null);
        } else {
            $dataImportMerchantFileTransfer->offsetSet($fieldName, null);
        }

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestBuilder())
            ->build()
            ->addDataImportMerchantFile($dataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);
    }

    /**
     * @return array<string, array<string>>
     */
    public static function getRequiredFieldsDataProvider(): array
    {
        return [
            'missing idUser' => ['idUser'],
            'missing merchantReference' => ['merchantReference'],
            'missing importerType' => ['importerType'],
            'missing fileInfo' => ['fileInfo'],
            'missing fileInfo.originalFileName' => ['fileInfo.originalFileName'],
            'missing fileInfo.contentType' => ['fileInfo.contentType'],
            'missing fileInfo.size' => ['fileInfo.size'],
            'missing fileInfo.content' => ['fileInfo.content'],
        ];
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionWithTransactionalTrue(): void
    {
        // Arrange
        $this->tester->ensureDataImportMerchantTablesAreEmpty();

        $invalidDataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $invalidDataImportMerchantFileTransfer->setMerchantReference('non-existing-merchant-reference');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer)
            ->setIsTransactional(true);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        $dataImportMerchantFilesCount = $this->tester->getDataImportMerchantFileQuery()->count();
        $this->assertSame(0, $dataImportMerchantFilesCount, 'No files should be saved when transaction is enabled and an error occurs');
    }

    /**
     * @return void
     */
    public function testCreateDataImportMerchantFileCollectionWithTransactionalFalse(): void
    {
        // Arrange
        $this->tester->ensureDataImportMerchantTablesAreEmpty();

        $invalidDataImportMerchantFileTransfer = $this->tester->createValidDataImportMerchantFile();
        $invalidDataImportMerchantFileTransfer->setMerchantReference('non-existing-merchant-reference');

        $dataImportMerchantFileCollectionRequestTransfer = (new DataImportMerchantFileCollectionRequestTransfer())
            ->addDataImportMerchantFile($this->tester->createValidDataImportMerchantFile())
            ->addDataImportMerchantFile($invalidDataImportMerchantFileTransfer)
            ->setIsTransactional(false);

        // Act
        $dataImportMerchantFileCollectionResponseTransfer = $this->tester
            ->getFacade()
            ->createDataImportMerchantFileCollection($dataImportMerchantFileCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $dataImportMerchantFileCollectionResponseTransfer->getErrors());

        $dataImportMerchantFilesCount = $this->tester->getDataImportMerchantFileQuery()->count();
        $this->assertSame(1, $dataImportMerchantFilesCount, 'Valid files should be saved when transaction is disabled, even if another file has errors');
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface
     */
    protected function getDataImportMerchantFileValidatorPluginMock(): DataImportMerchantFileValidatorPluginInterface
    {
        $dataImportMerchantFileValidatorPluginMock = $this
            ->getMockBuilder(DataImportMerchantFileValidatorPluginInterface::class)
            ->getMock();

        $dataImportMerchantFileValidatorPluginMock
            ->expects($this->once())
            ->method('validate')
            ->willReturnCallback(function (DataImportMerchantFileCollectionResponseTransfer $transfer) {
                return $transfer;
            });

        return $dataImportMerchantFileValidatorPluginMock;
    }

    /**
     * @return \Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface
     */
    protected function getDataImportMerchantFileRequestExpanderPluginMock(): DataImportMerchantFileRequestExpanderPluginInterface
    {
        $dataImportMerchantFileRequestExpanderPluginMock = $this
            ->getMockBuilder(DataImportMerchantFileRequestExpanderPluginInterface::class)
            ->getMock();

        $dataImportMerchantFileRequestExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function (DataImportMerchantFileCollectionRequestTransfer $transfer) {
                return $transfer;
            });

        return $dataImportMerchantFileRequestExpanderPluginMock;
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToFileSystemServiceInterface
     */
    protected function createFileSystemServiceMock(): DataImportMerchantToFileSystemServiceInterface
    {
        $fileSystemServiceMock = $this->createMock(DataImportMerchantToFileSystemServiceInterface::class);
        $fileSystemServiceMock->method('write');

        return $fileSystemServiceMock;
    }
}
