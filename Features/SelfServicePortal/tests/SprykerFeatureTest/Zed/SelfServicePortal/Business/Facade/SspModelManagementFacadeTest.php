<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceInterface;
use Spryker\Zed\FileManager\FileManagerDependencyProvider;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group SspModelManagementFacadeTest
 */
class SspModelManagementFacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade
     */
    protected $selfServicePortalFacade;

    protected function _before(): void
    {
        $this->selfServicePortalFacade = new SelfServicePortalFacade();

        $serviceFileSystemMock = $this->createMock(FileManagerToFileSystemServiceInterface::class);
        $serviceFileSystemMock->method('write')->willReturnCallback(function (): void {
        });
        $this->tester->setDependency(FileManagerDependencyProvider::SERVICE_FILE_SYSTEM, $serviceFileSystemMock);
    }

    /**
     * @dataProvider modelCreatedSuccessfulCollectionDataProvider
     *
     * @param array<mixed> $sspModelData
     * @param int $expectedModelCount
     * @param string $expectedName
     * @param array<string> $expectedValidationErrors
     *
     * @return void
     */
    public function testCreateSspModelCollectionIsSuccessful(
        array $sspModelData,
        int $expectedModelCount,
        string $expectedName,
        array $expectedValidationErrors
    ): void {
        // Arrange
        $sspModelTransfer = (new SspModelTransfer())
            ->setName($sspModelData['name'])
            ->setCode($sspModelData['code']);

        if (isset($sspModelData['generateImage']) && $sspModelData['generateImage']) {
            $fileTransfer = $this->tester->haveFile();
            $sspModelTransfer->setImage($fileTransfer);
        }

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->createSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount($expectedModelCount, $sspModelCollectionResponseTransfer->getSspModels());

        if ($expectedModelCount > 0) {
            $createdModelTransfer = $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current();
            $this->assertNotNull($createdModelTransfer->getIdSspModel());
            $this->assertSame($expectedName, $createdModelTransfer->getName());
            $this->assertNotNull($createdModelTransfer->getReference());

            if (isset($sspModelData['generateImage']) && $sspModelData['generateImage']) {
                $this->assertNotNull($createdModelTransfer->getImage(), 'Image should be attached to the created model');
                $this->assertSame(
                    $sspModelTransfer->getImage()->getIdFile(),
                    $createdModelTransfer->getImage()->getIdFile(),
                    'Created model should reference the same image file ID as input',
                );
            } else {
                $this->assertNull($createdModelTransfer->getImage(), 'No image should be attached when not provided');
            }
        }

        $this->assertEmpty($sspModelCollectionResponseTransfer->getErrors()->getIterator());
    }

    /**
     * @dataProvider modelCreationFailureDataProvider
     *
     * @param array<mixed> $sspModelData
     * @param array<string> $expectedValidationErrors
     *
     * @return void
     */
    public function testCreateSspModelCollectionFailsWithInvalidData(
        array $sspModelData,
        array $expectedValidationErrors
    ): void {
        // Arrange
        $sspModelTransfer = (new SspModelTransfer())
            ->setName($sspModelData['name'] ?? null)
            ->setCode($sspModelData['code'] ?? null);

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->createSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertInstanceOf(SspModelCollectionResponseTransfer::class, $sspModelCollectionResponseTransfer);
        $this->assertCount(0, $sspModelCollectionResponseTransfer->getSspModels(), 'No models should be created when validation fails');
        $this->assertGreaterThan(0, $sspModelCollectionResponseTransfer->getErrors()->count(), 'Validation errors should be present');

        $actualErrors = array_map(
            fn ($errorTransfer) => $errorTransfer->getMessage(),
            $sspModelCollectionResponseTransfer->getErrors()->getArrayCopy(),
        );

        foreach ($expectedValidationErrors as $expectedError) {
            $this->assertContains($expectedError, $actualErrors, "Expected validation error '{$expectedError}' not found");
        }
    }

    /**
     * @return array<mixed>
     */
    protected function modelCreatedSuccessfulCollectionDataProvider(): array
    {
        return [
            'success with all fields' => [
                'sspModelData' => [
                    'name' => 'Test Model',
                    'code' => 'TM001',
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Test Model',
                'expectedValidationErrors' => [],
            ],
            'success without code field' => [
                'sspModelData' => [
                    'name' => 'Test Model',
                    'code' => null,
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Test Model',
                'expectedValidationErrors' => [],
            ],
            'success with image' => [
                'sspModelData' => [
                    'name' => 'Test Model with Image',
                    'code' => 'TMI001',
                    'generateImage' => true,
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Test Model with Image',
                'expectedValidationErrors' => [],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function modelCreationFailureDataProvider(): array
    {
        return [
            'missing name' => [
                'sspModelData' => [
                    'name' => null,
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name is required.',
                ],
            ],
            'empty name' => [
                'sspModelData' => [
                    'name' => '',
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name is required.',
                ],
            ],
            'too long name' => [
                'sspModelData' => [
                    'name' => 'Test name that exceeds the maximum length of the model name field which is typically set to 255 characters. This name is intentionally made very long to trigger the validation error for exceeding the maximum length allowed for the model name field in the self-service portal. This should result in a validation error indicating that the model name is too long.',
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name cannot be longer than 255 characters.',
                ],
            ],
            'too long code' => [
                'sspModelData' => [
                    'name' => 'Test Name',
                    'code' => 'Too long code that exceeds the maximum length of the model code field which is typically set to 255 characters. This code is intentionally made very long to trigger the validation error for exceeding the maximum length allowed for the model code field in the self-service portal. This should result in a validation error indicating that the model code is too long.',
                ],
                'expectedValidationErrors' => [
                    'Model code cannot be longer than 100 characters.',
                ],
            ],
            'both name and code missing' => [
                'sspModelData' => [
                    'name' => null,
                    'code' => null,
                ],
                'expectedValidationErrors' => [
                    'Model name is required.',
                ],
            ],
        ];
    }

    /**
     * @dataProvider modelUpdatedSuccessfulCollectionDataProvider
     *
     * @param array<mixed> $sspModelData
     * @param int $expectedModelCount
     * @param string $expectedName
     * @param array<string> $expectedValidationErrors
     *
     * @return void
     */
    public function testUpdateSspModelCollectionIsSuccessful(
        array $sspModelData,
        int $expectedModelCount,
        string $expectedName,
        array $expectedValidationErrors
    ): void {
        // Arrange
        $existingSspModelTransfer = $this->tester->haveSspModel([
            'name' => 'Original Model Name',
            'code' => 'ORIGINAL_CODE',
        ]);

        $sspModelTransfer = (new SspModelTransfer())
            ->setIdSspModel($existingSspModelTransfer->getIdSspModel())
            ->setName($sspModelData['name'])
            ->setCode($sspModelData['code']);

        if (isset($sspModelData['generateImage']) && $sspModelData['generateImage']) {
            $fileTransfer = $this->tester->haveFile();
            $sspModelTransfer->setImage($fileTransfer);
        }

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount($expectedModelCount, $sspModelCollectionResponseTransfer->getSspModels());

        if ($expectedModelCount > 0) {
            $updatedModelTransfer = $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current();
            $this->assertNotNull($updatedModelTransfer->getIdSspModel());
            $this->assertSame($expectedName, $updatedModelTransfer->getName());
            $this->assertSame($existingSspModelTransfer->getReference(), $updatedModelTransfer->getReference());

            if (isset($sspModelData['generateImage']) && $sspModelData['generateImage']) {
                $this->assertNotNull($updatedModelTransfer->getImage(), 'Image should be attached to the updated model');
                $this->assertSame(
                    $sspModelTransfer->getImage()->getIdFile(),
                    $updatedModelTransfer->getImage()->getIdFile(),
                    'Updated model should reference the same image file ID as input',
                );
            }
        }

        $this->assertEmpty($sspModelCollectionResponseTransfer->getErrors()->getIterator());
    }

    /**
     * @dataProvider modelUpdateFailureDataProvider
     *
     * @param array<mixed> $sspModelData
     * @param array<string> $expectedValidationErrors
     *
     * @return void
     */
    public function testUpdateSspModelCollectionFailsWithInvalidDataReturnsResponseWithError(
        array $sspModelData,
        array $expectedValidationErrors
    ): void {
        // Arrange
        $existingSspModelTransfer = $this->tester->haveSspModel([
            'name' => 'Original Model Name',
            'code' => 'ORIGINAL_CODE',
        ]);

        $sspModelTransfer = (new SspModelTransfer())
            ->setIdSspModel($existingSspModelTransfer->getIdSspModel())
            ->setName($sspModelData['name'] ?? null)
            ->setCode($sspModelData['code'] ?? null);

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertInstanceOf(SspModelCollectionResponseTransfer::class, $sspModelCollectionResponseTransfer);
        $this->assertCount(0, $sspModelCollectionResponseTransfer->getSspModels(), 'No models should be updated when validation fails');
        $this->assertGreaterThan(0, $sspModelCollectionResponseTransfer->getErrors()->count(), 'Validation errors should be present');

        $actualErrors = array_map(
            fn ($errorTransfer) => $errorTransfer->getMessage(),
            $sspModelCollectionResponseTransfer->getErrors()->getArrayCopy(),
        );

        foreach ($expectedValidationErrors as $expectedError) {
            $this->assertContains($expectedError, $actualErrors, "Expected validation error '{$expectedError}' not found");
        }
    }

    public function testUpdateSspModelCollectionWithNonExistentModelReturnsResponseWithError(): void
    {
        // Arrange
        $sspModelTransfer = (new SspModelTransfer())
            ->setIdSspModel(99999)
            ->setName('Updated Model Name')
            ->setCode('UPDATED_CODE');

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertInstanceOf(SspModelCollectionResponseTransfer::class, $sspModelCollectionResponseTransfer);
        $this->assertCount(0, $sspModelCollectionResponseTransfer->getSspModels(), 'No models should be updated when model does not exist');
        $this->assertGreaterThan(0, $sspModelCollectionResponseTransfer->getErrors()->count(), 'Error should be present for non-existent model');
    }

    public function testUpdateSspModelCollectionPreservesReference(): void
    {
        // Arrange
        $existingSspModelTransfer = $this->tester->haveSspModel([
            'name' => 'Original Model Name',
            'code' => 'ORIGINAL_CODE',
        ]);

        $originalReference = $existingSspModelTransfer->getReference();

        $sspModelTransfer = (new SspModelTransfer())
            ->setIdSspModel($existingSspModelTransfer->getIdSspModel())
            ->setName('Updated Model Name')
            ->setCode('UPDATED_CODE');

        $sspModelCollectionRequestTransfer = (new SspModelCollectionRequestTransfer())
            ->addSspModel($sspModelTransfer);

        // Act
        $sspModelCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspModelCollection(
            $sspModelCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $sspModelCollectionResponseTransfer->getSspModels());

        $updatedModelTransfer = $sspModelCollectionResponseTransfer->getSspModels()->getIterator()->current();
        $this->assertSame($originalReference, $updatedModelTransfer->getReference(), 'Model reference should be preserved during update');
        $this->assertSame('Updated Model Name', $updatedModelTransfer->getName());
        $this->assertSame('UPDATED_CODE', $updatedModelTransfer->getCode());
    }

    /**
     * @return array<mixed>
     */
    protected function modelUpdatedSuccessfulCollectionDataProvider(): array
    {
        return [
            'success with all fields updated' => [
                'sspModelData' => [
                    'name' => 'Updated Model Name',
                    'code' => 'UPDATED_CODE',
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Updated Model Name',
                'expectedValidationErrors' => [],
            ],
            'success with only name updated' => [
                'sspModelData' => [
                    'name' => 'Updated Model Name Only',
                    'code' => null,
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Updated Model Name Only',
                'expectedValidationErrors' => [],
            ],
            'success with image update' => [
                'sspModelData' => [
                    'name' => 'Updated Model with Image',
                    'code' => 'UPDATED_IMAGE_CODE',
                    'generateImage' => true,
                ],
                'expectedModelCount' => 1,
                'expectedName' => 'Updated Model with Image',
                'expectedValidationErrors' => [],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    protected function modelUpdateFailureDataProvider(): array
    {
        return [
            'missing name' => [
                'sspModelData' => [
                    'name' => null,
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name is required.',
                ],
            ],
            'empty name' => [
                'sspModelData' => [
                    'name' => '',
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name is required.',
                ],
            ],
            'too long name' => [
                'sspModelData' => [
                    'name' => 'Test name that exceeds the maximum length of the model name field which is typically set to 255 characters. This name is intentionally made very long to trigger the validation error for exceeding the maximum length allowed for the model name field in the self-service portal. This should result in a validation error indicating that the model name is too long.',
                    'code' => 'VALID_CODE',
                ],
                'expectedValidationErrors' => [
                    'Model name cannot be longer than 255 characters.',
                ],
            ],
            'too long code' => [
                'sspModelData' => [
                    'name' => 'Valid Name',
                    'code' => 'Too long code that exceeds the maximum length of the model code field which is typically set to 255 characters. This code is intentionally made very long to trigger the validation error for exceeding the maximum length allowed for the model code field in the self-service portal. This should result in a validation error indicating that the model code is too long.',
                ],
                'expectedValidationErrors' => [
                    'Model code cannot be longer than 100 characters.',
                ],
            ],
        ];
    }
}
