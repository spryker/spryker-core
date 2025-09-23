<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImportMerchant;

use Codeception\Actor;
use Generated\Shared\DataBuilder\DataImportMerchantFileBuilder;
use Generated\Shared\DataBuilder\DataImportMerchantFileInfoBuilder;
use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile;
use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\DataImportMerchant\Business\DataImportMerchantFacadeInterface getFacade(?string $moduleName = null)
 * @method \Generated\Shared\Transfer\UserTransfer haveUser($seed = null)
 * @method \Generated\Shared\Transfer\MerchantTransfer haveMerchant(array $seed = [])
 *
 * @SuppressWarnings(\SprykerTest\Zed\DataImport\PHPMD)
 */
class DataImportMerchantBusinessTester extends Actor
{
    use _generated\DataImportMerchantBusinessTesterActions;

    /**
     * @param string|null $merchantReference
     * @param int|null $idUser
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileTransfer
     */
    public function createValidDataImportMerchantFile(?string $merchantReference = null, ?int $idUser = null): DataImportMerchantFileTransfer
    {
        return (new DataImportMerchantFileBuilder())
            ->withFileInfo((new DataImportMerchantFileInfoBuilder())->build()->toArray())
            ->build()
            ->setIdUser($idUser ?? $this->haveUser()->getIdUserOrFail())
            ->setMerchantReference($merchantReference ?? $this->haveMerchant()->getMerchantReference());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
     *
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFile
     */
    public function findDataImportMerchantEntity(
        DataImportMerchantFileTransfer $dataImportMerchantFileTransfer
    ): SpyDataImportMerchantFile {
        return $this->getDataImportMerchantFileQuery()
            ->filterByIdDataImportMerchantFile($dataImportMerchantFileTransfer->getIdDataImportMerchantFileOrFail())
            ->findOne();
    }

    /**
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    public function getDataImportMerchantFileQuery(): SpyDataImportMerchantFileQuery
    {
        return SpyDataImportMerchantFileQuery::create();
    }

    /**
     * @return void
     */
    public function ensureDataImportMerchantTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getDataImportMerchantFileQuery(),
        );
    }
}
