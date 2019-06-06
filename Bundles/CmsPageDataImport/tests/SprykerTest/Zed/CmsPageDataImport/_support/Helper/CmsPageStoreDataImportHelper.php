<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CmsPageDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;

class CmsPageStoreDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableCmsPageStoreIsEmpty(): void
    {
        $cmsPageStoreQuery = $this->getCmsPageStoreQuery();
        $cmsPageStoreQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableCmsPageStoreContainsData(): void
    {
        $cmsPageStoreQuery = $this->getCmsPageStoreQuery();
        $this->assertTrue(($cmsPageStoreQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery
     */
    protected function getCmsPageStoreQuery(): SpyCmsPageStoreQuery
    {
        return SpyCmsPageStoreQuery::create();
    }
}
