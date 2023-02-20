<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchHttp\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SearchHttpConfigBuilder;
use Generated\Shared\DataBuilder\SearchHttpConfigCollectionBuilder;
use Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Orm\Zed\SearchHttp\Persistence\SpySearchHttpConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SearchHttpBusinessHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigCollectionTransfer
     */
    public function createSearchHttpConfigCollectionTransfer(array $seed = []): SearchHttpConfigCollectionTransfer
    {
        return (new SearchHttpConfigCollectionBuilder())->seed($seed)->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\SearchHttpConfigTransfer
     */
    public function createSearchHttpConfigTransfer(array $seed = []): SearchHttpConfigTransfer
    {
        return (new SearchHttpConfigBuilder())->seed($seed)->build();
    }

    /**
     * @param array $seed
     *
     * @return void
     */
    public function haveSearchHttpConfig(array $seed): void
    {
        $searchHttpConfigEntity = (new SpySearchHttpConfig())
            ->setStoreName('test_store_name')
            ->setData($this->createSearchHttpConfigCollectionTransfer($seed)->toArray());

        $searchHttpConfigEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($searchHttpConfigEntity): void {
            $searchHttpConfigEntity->delete();
        });
    }
}
