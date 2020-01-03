<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CommentDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Comment\Persistence\SpyCommentQuery;

class CommentDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertCommentDatabaseTablesContainsData(): void
    {
        $commentQuery = $this->getCommentQuery();

        $this->assertTrue(
            $commentQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function getCommentQuery(): SpyCommentQuery
    {
        return SpyCommentQuery::create();
    }
}
