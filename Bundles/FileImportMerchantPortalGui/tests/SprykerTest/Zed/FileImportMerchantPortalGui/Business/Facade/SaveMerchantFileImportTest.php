<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileImportMerchantPortalGui\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group FileImportMerchantPortalGui
 * @group Business
 * @group Facade
 * @group SaveMerchantFileImportTest
 * Add your own group annotations below this line
 */
class SaveMerchantFileImportTest extends Unit
{
    /**
     * @uses \Spryker\Zed\FileImportMerchantPortalGui\Business\Saver\MerchantFileImportSaver::GENERIC_ERROR_MESSAGE
     *
     * @var string
     */
    protected const GENERIC_ERROR_MESSAGE = 'An error occurred while saving the merchant file import.';

    /**
     * @var \SprykerTest\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _tearDown(): void
    {
        $this->tester->deleteAllMerchantFileImports();
    }

    /**
     * @return void
     */
    public function testSavesMerchantFileImport(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantFileTransfer = $this->tester->haveMerchantFile([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
        ]);

        $merchantFileImportTransfer = $this->tester->buildMerchantFileImportTransfer([
            MerchantFileImportTransfer::FK_MERCHANT_FILE => $merchantFileTransfer->getIdMerchantFile(),
        ]);

        // Act
        $merchantFileImportResponseTransfer = $this->tester->getFacade()
            ->saveMerchantFileImport($merchantFileImportTransfer);

        // Assert
        $this->assertTrue($merchantFileImportResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $merchantFileImportResponseTransfer->getMessages());
    }

    /**
     * @dataProvider provideMerchantFileImportMissingSeedData
     *
     * @param array $merchantFileImportSeedData
     *
     * @return void
     */
    public function testReturnsGenericErrorWhenRequiredDataIsMissing(array $merchantFileImportSeedData): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantFileTransfer = $this->tester->haveMerchantFile([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
        ]);

        $merchantFileImportTransfer = $this->tester->buildMerchantFileImportTransfer([
            MerchantFileImportTransfer::FK_MERCHANT_FILE => $merchantFileTransfer->getIdMerchantFile(),
            ...$merchantFileImportSeedData,
        ]);

        // Act
        $merchantFileImportResponseTransfer = $this->tester->getFacade()
            ->saveMerchantFileImport($merchantFileImportTransfer);

        // Assert
        $this->assertFalse(
            $merchantFileImportResponseTransfer->getIsSuccessful(),
            'Expected the response to be unsuccessful when required data is missing.',
        );
        $this->assertCount(
            1,
            $merchantFileImportResponseTransfer->getMessages(),
            'Expected one error message when required data is missing.',
        );
        $this->assertEquals(
            static::GENERIC_ERROR_MESSAGE,
            $merchantFileImportResponseTransfer->getMessages()->offsetGet(0)->getMessage(),
            'Expected generic error message does not match the expected error message.',
        );
    }

    /**
     * @return array
     */
    public static function provideMerchantFileImportMissingSeedData(): array
    {
        return [
            'missing merchant file id' => [[MerchantFileImportTransfer::FK_MERCHANT_FILE => null]],
            'missing entity type' => [[MerchantFileImportTransfer::ENTITY_TYPE => null]],
            'missing status' => [[MerchantFileImportTransfer::STATUS => null]],
            'missing multiple fields' => [
                [
                    MerchantFileImportTransfer::FK_MERCHANT_FILE => null,
                    MerchantFileImportTransfer::ENTITY_TYPE => null,
                    MerchantFileImportTransfer::STATUS => null,
                ],
            ],
        ];
    }
}
