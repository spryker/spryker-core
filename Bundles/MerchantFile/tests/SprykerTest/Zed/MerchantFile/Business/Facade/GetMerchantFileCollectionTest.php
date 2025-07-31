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
 * @group GetMerchantFileCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantFileCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantFile\MerchantFileBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider provideMerchantFileCountData
     *
     * @param int $inputMerchantFileCount
     * @param int $expectedMerchantFileCount
     *
     * @return void
     */
    public function testSuccessfullyGetsMerchantFileCollection(
        int $inputMerchantFileCount,
        int $expectedMerchantFileCount
    ): void {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $userTransfer = $this->tester->haveUser();
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $merchantFileIds = [];
        $uuids = [];
        $types = [];

        for ($i = 0; $i < $inputMerchantFileCount; $i++) {
            $merchantFileTransfer = $this->tester->haveMerchantFile([
                MerchantFileTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantFileTransfer::FK_USER => $merchantUserTransfer->getIdUser(),
            ]);

            $merchantFileIds[$i] = $merchantFileTransfer->getIdMerchantFile();
            $uuids[$i] = $merchantFileTransfer->getUuid();
            $types[$i] = $merchantFileTransfer->getType();
        }

        $merchantFileConditionsTransfer = (new MerchantFileConditionsTransfer())
            ->setMerchantFileIds($merchantFileIds)
            ->setUuids($uuids)
            ->setTypes($types);

        $merchantFileCriteriaTransfer = (new MerchantFileCriteriaTransfer())
            ->setMerchantFileConditions($merchantFileConditionsTransfer);

        // Act
        $merchantFileCollectionTransfer = $this->tester->getFacade()
            ->getMerchantFileCollection($merchantFileCriteriaTransfer);

        // Assert
        $this->assertCount($expectedMerchantFileCount, $merchantFileCollectionTransfer->getMerchantFiles());
    }

    /**
     * @dataProvider provideNotExistingMerchantFileCriteria
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return void
     */
    public function testReturnsEmptyCollectionWhenMerchantFilesNotFound(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): void {
        // Act
        $merchantFileCollectionTransfer = $this->tester->getFacade()
            ->getMerchantFileCollection($merchantFileCriteriaTransfer);

        // Assert
        $this->assertCount(0, $merchantFileCollectionTransfer->getMerchantFiles());
    }

    /**
     * @return array
     */
    public static function provideMerchantFileCountData(): array
    {
        return [
            '1 merchant file' => [1, 1],
            '3 merchant files' => [3, 3],
            '5 merchant files' => [5, 5],
        ];
    }

    /**
     * @return array
     */
    public static function provideNotExistingMerchantFileCriteria(): array
    {
        return [
            'get by ID' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addMerchantFileId(9999),
                    ),
            ],
            'get by UUID' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addUuid('non-existing-uuid'),
                    ),
            ],
            'get by type' => [
                (new MerchantFileCriteriaTransfer())
                    ->setMerchantFileConditions(
                        (new MerchantFileConditionsTransfer())->addType('non-existing-type'),
                    ),
            ],
            'get by multiple conditions' => [
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
