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

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantFile
 * @group Business
 * @group Facade
 * @group FindMerchantFileTest
 * Add your own group annotations below this line
 */
class FindMerchantFileTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantFile\MerchantFileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSuccessfullyFindsMerchantFile(): void
    {
        // Arrange
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
        $foundMerchantFileTransfer = $this->tester->getFacade()->findMerchantFile($merchantFileCriteriaTransfer);

        // Assert
        $this->assertNotNull($foundMerchantFileTransfer);
    }

    /**
     * @dataProvider provideNotExistingMerchantFileCriteria
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return void
     */
    public function testFindMerchantFileReturnsNullWhenMerchantFileNotExists(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): void {
        // Act
        $merchantFileTransfer = $this->tester->getFacade()->findMerchantFile($merchantFileCriteriaTransfer);

        // Assert
        $this->assertNull($merchantFileTransfer);
    }

    /**
     * @return array
     */
    public static function provideNotExistingMerchantFileCriteria(): array
    {
        return [
            'find by ID' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addMerchantFileId(9999),
                    ),
            ],
            'find by UUID' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addUuid('non-existing-uuid'),
                    ),
            ],
            'find by type' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addType('non-existing-type'),
                    ),
            ],
            'find by multiple conditions' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())
                            ->addMerchantFileId(9999)
                            ->addUuid('non-existing-uuid')
                            ->addType('non-existing-type'),
                    ),
            ],
        ];
    }
}
