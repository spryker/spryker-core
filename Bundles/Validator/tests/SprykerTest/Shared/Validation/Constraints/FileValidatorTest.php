<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Validator\Constraints;

use Codeception\Configuration;
use Spryker\Shared\Validator\Constraints\File;
use Spryker\Shared\Validator\Constraints\FileValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Validator
 * @group Constraints
 * @group FileValidatorTest
 * Add your own group annotations below this line
 */
class FileValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var string
     */
    protected const ERROR_MIME_TYPES_MESSAGE = 'ERROR_MIME_TYPES_MESSAGE';

    /**
     * @var string
     */
    protected const ERROR_EXTENSIONS_MESSAGE = 'ERROR_EXTENSIONS_MESSAGE';

    /**
     * @var string
     */
    protected const ERROR_EMPTY_TYPE_MESSAGE = 'ERROR_EMPTY_TYPE_MESSAGE';

    /**
     * @var string
     */
    protected const MIME_TYPE_PLAIN = 'text/plain';

    /**
     * @var string
     */
    protected const MIME_TYPE_PNG = 'image/png';

    /**
     * @var string
     */
    protected const EXTENSION_TXT = 'txt';

    /**
     * @var string
     */
    protected const EXTENSION_PNG = 'png';

    /**
     * @var string
     */
    protected const TEST_FILE_NAME = 'file-validator-test.txt';

    /**
     * @var string
     */
    protected string $filePath;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filePath = $this->createFile();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->removeFile($this->filePath);
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenMimeTypeIsNotAllowed(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint([static::MIME_TYPE_PNG]);
        $violation = $this->buildViolation(static::ERROR_MIME_TYPES_MESSAGE)->setParameters([
            '{{ file }}' => sprintf('"%s"', $this->filePath),
            '{{ type }}' => sprintf('"%s"', static::MIME_TYPE_PLAIN),
            '{{ types }}' => sprintf('"%s"', static::MIME_TYPE_PNG),
            '{{ name }}' => sprintf('"%s"', $uploadedFile->getClientOriginalName()),
        ])->setCode(File::INVALID_MIME_TYPE_ERROR);

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $violation->assertRaised();
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessWhenMimeTypeIsAllowed(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint([static::MIME_TYPE_PLAIN]);

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $this->assertNoViolation();
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenExtensionIsNotAllowed(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint([], [static::EXTENSION_PNG]);
        $violation = $this->buildViolation(static::ERROR_EXTENSIONS_MESSAGE)->setParameters([
            '{{ file }}' => sprintf('"%s"', $this->filePath),
            '{{ extension }}' => sprintf('"%s"', static::EXTENSION_TXT),
            '{{ extensions }}' => sprintf('"%s"', static::EXTENSION_PNG),
            '{{ name }}' => sprintf('"%s"', $uploadedFile->getClientOriginalName()),
        ])->setCode(File::INVALID_EXTENSION_ERROR);

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $violation->assertRaised();
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessWhenExtensionIsAllowed(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint([], [static::EXTENSION_TXT]);

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $this->assertNoViolation();
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenIsEmptyMimeTypesConstraintEnabledAndEmptyListsOfMimeTypesAndExtensionsAreProvided(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint([], [], true);

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $this->buildViolation(static::ERROR_EMPTY_TYPE_MESSAGE)
            ->assertRaised();
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessWhenIsEmptyMimeTypesConstraintDisabledAndEmptyListsOfMimeTypesAndExtensionsAreProvided(): void
    {
        // Arrange
        $uploadedFile = $this->createUploadedFile(static::MIME_TYPE_PLAIN);
        $fileConstraint = $this->createFileConstraint();

        // Act
        $this->validator->validate($uploadedFile, $fileConstraint);

        // Assert
        $this->assertNoViolation();
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new FileValidator();
    }

    /**
     * @param list<string> $mimeTypes
     * @param list<string> $extensions
     * @param bool $isEmptyTypesValidationEnabled
     *
     * @return \Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\File
     */
    protected function createFileConstraint(array $mimeTypes = [], array $extensions = [], bool $isEmptyTypesValidationEnabled = false): File
    {
        return new File([
            'mimeTypes' => $mimeTypes,
            'mimeTypesMessage' => static::ERROR_MIME_TYPES_MESSAGE,
            'extensions' => $extensions,
            'extensionsMessage' => static::ERROR_EXTENSIONS_MESSAGE,
            'isEmptyTypesValidationEnabled' => $isEmptyTypesValidationEnabled,
            'emptyTypesMessage' => static::ERROR_EMPTY_TYPE_MESSAGE,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected function createUploadedFile(string $mimeType): UploadedFile
    {
        return new UploadedFile($this->filePath, static::TEST_FILE_NAME, $mimeType, null, true);
    }

    /**
     * @return string
     */
    protected function createFile(): string
    {
        if (!is_dir(Configuration::dataDir())) {
            mkdir(Configuration::dataDir(), 0777, true);
        }

        $filePath = sprintf('%s%s', Configuration::dataDir(), static::TEST_FILE_NAME);
        file_put_contents($filePath, 'test');

        return $filePath;
    }

    /**
     * @param string $filePath
     *
     * @return void
     */
    protected function removeFile(string $filePath): void
    {
        unlink($filePath);
    }
}
