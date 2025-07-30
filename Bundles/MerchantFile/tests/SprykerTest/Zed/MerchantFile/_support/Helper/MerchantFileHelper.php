<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantFile\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantFileBuilder;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFile;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantFileHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function haveMerchantFile(array $seedData = []): MerchantFileTransfer
    {
        $merchantFileTransfer = $this->buildMerchantFileTransfer($seedData);

        $merchantFileEntity = $this->getMerchantFileEntity();
        $merchantFileEntity->fromArray($merchantFileTransfer->toArray());

        $merchantFileEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantFileEntity): void {
            $merchantFileEntity->delete();
        });

        return $merchantFileTransfer->fromArray($merchantFileEntity->toArray(), true);
    }

    /**
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile
     */
    protected function getMerchantFileEntity(): SpyMerchantFile
    {
        return new SpyMerchantFile();
    }

    /**
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery
     */
    protected function getMerchantFileQuery(): SpyMerchantFileQuery
    {
        return SpyMerchantFileQuery::create();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    public function buildMerchantFileTransfer(array $seedData = []): MerchantFileTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer */
        $merchantFileTransfer = (new MerchantFileBuilder($seedData))->build();

        return $merchantFileTransfer;
    }
}
