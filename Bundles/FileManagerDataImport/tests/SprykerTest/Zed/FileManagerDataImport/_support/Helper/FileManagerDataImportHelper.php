<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\FileManagerDataImport\Helper;

use Codeception\Module;
use Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery;

class FileManagerDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $this->getMimeTypeQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $mimeTypeQuery = $this->getMimeTypeQuery();
        $this->assertTrue(($mimeTypeQuery->count() > 0), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery
     */
    protected function getMimeTypeQuery(): SpyMimeTypeQuery
    {
        return SpyMimeTypeQuery::create();
    }
}
