<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileImportMerchantPortalGui\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantFileImportBuilder;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class FileImportMerchantPortalGuiHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function haveMerchantFileImport(array $seedData = []): MerchantFileImportTransfer
    {
        $merchantFileImportTransfer = $this->buildMerchantFileImportTransfer($seedData);

        $merchantFileImportEntity = $this->getMerchantFileImportEntity();
        $merchantFileImportEntity->fromArray($merchantFileImportTransfer->toArray());

        $merchantFileImportEntity->save();

        $this->getDataCleanupHelper()->addCleanup(static function () use ($merchantFileImportEntity): void {
            $merchantFileImportEntity->delete();
        });

        return $merchantFileImportTransfer->fromArray($merchantFileImportEntity->toArray(), true);
    }

    /**
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport
     */
    protected function getMerchantFileImportEntity(): SpyMerchantFileImport
    {
        return new SpyMerchantFileImport();
    }

    /**
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    protected function getMerchantFileImportQuery(): SpyMerchantFileImportQuery
    {
        return SpyMerchantFileImportQuery::create();
    }

    /**
     * @return void
     */
    public function deleteAllMerchantFileImports(): void
    {
        $this->getMerchantFileImportQuery()->deleteAll();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function buildMerchantFileImportTransfer(array $seedData = []): MerchantFileImportTransfer
    {
        return (new MerchantFileImportBuilder($seedData))->build();
    }
}
