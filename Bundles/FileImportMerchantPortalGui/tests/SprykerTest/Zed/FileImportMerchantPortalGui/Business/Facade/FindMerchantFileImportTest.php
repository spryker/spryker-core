<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileImportMerchantPortalGui\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantFileImportConditionsTransfer;
use Generated\Shared\Transfer\MerchantFileImportCriteriaTransfer;
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
 * @group FindMerchantFileImportTest
 * Add your own group annotations below this line
 */
class FindMerchantFileImportTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReturnsExpectedMerchantFileImportWhenExists(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantFileTransfer = $this->tester->haveMerchantFile([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
        ]);
        $merchantFileImportTransfer = $this->tester->haveMerchantFileImport([
            MerchantFileImportTransfer::FK_MERCHANT_FILE => $merchantFileTransfer->getIdMerchantFile(),
        ]);

        $merchantFileImportConditionsTransfer = (new MerchantFileImportConditionsTransfer())
            ->addIdMerchantFileImport($merchantFileImportTransfer->getIdMerchantFileImport())
            ->addIdMerchantFile($merchantFileTransfer->getIdMerchantFile())
            ->addUuid($merchantFileImportTransfer->getUuid())
            ->addEntityType($merchantFileImportTransfer->getEntityType())
            ->addStatus($merchantFileImportTransfer->getStatus());

        $merchantFileImportCriteriaTransfer = (new MerchantFileImportCriteriaTransfer())
            ->setMerchantFileImportConditions($merchantFileImportConditionsTransfer);

        // Act
        $foundMerchantFileImportTransfer = $this->tester->getFacade()
            ->findMerchantFileImport($merchantFileImportCriteriaTransfer);

        // Assert
        $this->assertNotNull($foundMerchantFileImportTransfer, 'Merchant file import should be found.');
        $this->assertNotNull($foundMerchantFileImportTransfer->getMerchantFile(), 'Merchant file should be associated with the import.');
    }

    /**
     * @return void
     */
    public function testReturnsNullWhenMerchantFileImportNotExists(): void
    {
        // Arrange
        $merchantFileImportConditionsTransfer = (new MerchantFileImportConditionsTransfer())
            ->addIdMerchantFileImport(9999)
            ->addUuid('non-existing-uuid')
            ->addEntityType('non-existing-entity-type')
            ->addStatus('non-existing-status');

        $merchantFileImportCriteriaTransfer = (new MerchantFileImportCriteriaTransfer())
            ->setMerchantFileImportConditions($merchantFileImportConditionsTransfer);

        // Act
        $merchantFileImportTransfer = $this->tester->getFacade()
            ->findMerchantFileImport($merchantFileImportCriteriaTransfer);

        // Assert
        $this->assertNull($merchantFileImportTransfer, 'Merchant file import should not be found.');
    }

    /**
     * @dataProvider provideMerchantFileImportExtraRecordsCountForFiltering
     *
     * @param int $merchantFileImportExtraRecordsCount
     *
     * @return void
     */
    public function testReturnsMerchantFileImportWhenFiltersAmongMerchantFileImports(
        int $merchantFileImportExtraRecordsCount
    ): void {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);
        $merchantFileTransfer = $this->tester->haveMerchantFile([
            MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
        ]);
        $merchantFileImportTransfer = $this->tester->haveMerchantFileImport([
            MerchantFileImportTransfer::FK_MERCHANT_FILE => $merchantFileTransfer->getIdMerchantFile(),
        ]);

        for ($i = 0; $i < $merchantFileImportExtraRecordsCount; $i++) {
            $this->tester->haveMerchantFile([
                MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
            ]);
            $this->tester->haveMerchantFileImport([
                MerchantFileImportTransfer::FK_MERCHANT_FILE => $merchantFileTransfer->getIdMerchantFile(),
            ]);
        }

        $merchantFileImportConditionsTransfer = (new MerchantFileImportConditionsTransfer())
            ->addIdMerchantFileImport($merchantFileImportTransfer->getIdMerchantFileImport())
            ->addIdMerchantFile($merchantFileTransfer->getIdMerchantFile())
            ->addUuid($merchantFileImportTransfer->getUuid())
            ->addEntityType($merchantFileImportTransfer->getEntityType())
            ->addStatus($merchantFileImportTransfer->getStatus());

        $merchantFileImportCriteriaTransfer = (new MerchantFileImportCriteriaTransfer())
            ->setMerchantFileImportConditions($merchantFileImportConditionsTransfer);

        // Act
        $foundMerchantFileImportTransfer = $this->tester->getFacade()
            ->findMerchantFileImport($merchantFileImportCriteriaTransfer);

        // Assert
        $this->assertNotNull($foundMerchantFileImportTransfer, 'Merchant file import should be found.');
        $this->assertNotNull($foundMerchantFileImportTransfer->getMerchantFile(), 'Merchant file should be associated with the import.');
    }

    /**
     * @return array
     */
    public static function provideMerchantFileImportExtraRecordsCountForFiltering(): array
    {
        return [
            '2 merchant file imports' => [2],
            '5 merchant file imports' => [5],
        ];
    }
}
