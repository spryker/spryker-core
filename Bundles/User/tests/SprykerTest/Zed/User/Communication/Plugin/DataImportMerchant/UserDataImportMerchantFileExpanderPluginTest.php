<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Plugin\DataImportMerchant;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\User\Communication\Plugin\DataImportMerchant\UserDataImportMerchantFileExpanderPlugin;
use SprykerTest\Zed\User\UserCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Communication
 * @group Plugin
 * @group DataImportMerchant
 * @group UserDataImportMerchantFileExpanderPluginTest
 * Add your own group annotations below this line
 */
class UserDataImportMerchantFileExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\User\UserCommunicationTester
     */
    protected UserCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldExpandDataImportMerchantFileCollectionWithUserData(): void
    {
        // Arrange
        $userTransfer1 = $this->tester->haveUser();
        $userTransfer2 = $this->tester->haveUser();

        $dataImportMerchantFileCollectionTransfer = (new DataImportMerchantFileCollectionTransfer())
            ->addDataImportMerchantFile($this->createDataImportMerchantFileTransfer($userTransfer1))
            ->addDataImportMerchantFile($this->createDataImportMerchantFileTransfer($userTransfer2));

        // Act
        $dataImportMerchantFileCollectionTransfer = (new UserDataImportMerchantFileExpanderPlugin())
            ->expand($dataImportMerchantFileCollectionTransfer);

        // Assert
        $dataImportMerchantFileTransfers = $dataImportMerchantFileCollectionTransfer->getDataImportMerchantFiles();
        $this->assertSame($userTransfer1->getIdUser(), $dataImportMerchantFileTransfers->offsetGet(0)->getUser()->getIdUser());
        $this->assertSame($userTransfer2->getIdUser(), $dataImportMerchantFileTransfers->offsetGet(1)->getUser()->getIdUser());
    }

    /**
     * @return void
     */
    public function testExpandShouldThrowExceptionWhenDataImportMerchantFileTransferWithoutRequiredIdUser(): void
    {
        // Arrange
        $dataImportMerchantFileCollectionTransfer = (new DataImportMerchantFileCollectionTransfer())
            ->addDataImportMerchantFile(new DataImportMerchantFileTransfer());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idUser" of transfer `Generated\Shared\Transfer\DataImportMerchantFileTransfer` is null.');

        // Act
        (new UserDataImportMerchantFileExpanderPlugin())->expand($dataImportMerchantFileCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    protected function createDataImportMerchantFileTransfer(UserTransfer $userTransfer): DataImportMerchantFileTransfer
    {
        return (new DataImportMerchantFileTransfer())->setIdUser($userTransfer->getIdUser());
    }
}
