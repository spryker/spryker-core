<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommissionGui\Communication\Validator;

use Codeception\Test\Unit;
use Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidator;
use Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidatorInterface;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceBridge;
use SprykerTest\Zed\MerchantCommissionGui\MerchantCommissionGuiCommunicationTester;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommissionGui
 * @group Communication
 * @group Validator
 * @group MerchantCommissionCsvValidatorTest
 * Add your own group annotations below this line
 */
class MerchantCommissionCsvValidatorTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidator::ERROR_MESSAGE_HEADERS_MISSING
     *
     * @var string
     */
    protected const ERROR_MESSAGE_HEADERS_MISSING = 'The following headers are missing in the uploaded CSV file: %s.';

    /**
     * @uses \Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidator::ERROR_COLUMN_MISMATCH
     *
     * @var string
     */
    protected const ERROR_COLUMN_MISMATCH = 'The uploaded CSV file has incorrect structure. Headers and data rows must have the same number of columns.';

    /**
     * @var \SprykerTest\Zed\MerchantCommissionGui\MerchantCommissionGuiCommunicationTester
     */
    protected MerchantCommissionGuiCommunicationTester $tester;

    /**
     * @return void
     */
    public function testValidateMerchantCommissionCsvFileReturnsNoErrorsWhenHeaderIsCorrect(): void
    {
        // Arrange
        $uploadedFile = new UploadedFile(
            codecept_data_dir() . 'merchant_commission_import.csv',
            'merchant_commission_import.csv',
        );

        // Act
        $errorTransfers = $this->createMerchantCommissionCsvValidator()->validateMerchantCommissionCsvFile($uploadedFile);

        // Assert
        $this->assertCount(0, $errorTransfers);
    }

    /**
     * @return void
     */
    public function testValidateMerchantCommissionCsvFileReturnsErrorWhenHeaderIsInvalid(): void
    {
        // Arrange
        $uploadedFile = new UploadedFile(
            codecept_data_dir() . 'merchant_commission_import_invalid_header.csv',
            'merchant_commission_import_invalid_header.csv',
        );

        // Act
        $errorTransfers = $this->createMerchantCommissionCsvValidator()->validateMerchantCommissionCsvFile($uploadedFile);

        // Assert
        $this->assertCount(1, $errorTransfers);
        $this->assertSame(
            sprintf(static::ERROR_MESSAGE_HEADERS_MISSING, 'is_active, amount, calculator_type_plugin, group, merchants_allow_list'),
            $errorTransfers->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testValidateMerchantCommissionCsvFileReturnsErrorWhenColumnsAreMismatchedBetweenHeaderAndDataRows(): void
    {
        // Arrange
        $uploadedFile = new UploadedFile(
            codecept_data_dir() . 'merchant_commission_import_mismatched_columns.csv',
            'merchant_commission_import_mismatched_columns.csv',
        );

        // Act
        $errorTransfers = $this->createMerchantCommissionCsvValidator()->validateMerchantCommissionCsvFile($uploadedFile);

        // Assert
        $this->assertCount(1, $errorTransfers);
        $this->assertSame(
            static::ERROR_COLUMN_MISMATCH,
            $errorTransfers->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCommissionGui\Communication\Validator\MerchantCommissionCsvValidatorInterface
     */
    protected function createMerchantCommissionCsvValidator(): MerchantCommissionCsvValidatorInterface
    {
        return (new MerchantCommissionCsvValidator(
            $this->tester->getModuleConfig(),
            (new MerchantCommissionGuiToUtilCsvServiceBridge(
                $this->tester->getLocator()->utilCsv()->service(),
            )),
        ));
    }
}
