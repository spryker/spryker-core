<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ContentProductSetDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class ContentProductSetDataImportHelper extends Module
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @return void
     */
    public function ensureDatabaseTableIsEmpty(): void
    {
        $contentLocalizedQuery = $this->getContentLocalizedQuery();
        $contentLocalizedQuery->deleteAll();

        $contentQuery = $this->getContentQuery();
        $contentQuery->deleteAll();
    }

    /**
     * @param int $idLocale
     * @param array $data
     *
     * @return void
     */
    public function assertContentLocalizedHasSetId(int $idLocale, array $data): void
    {
        $contentLocalized = $this->getContentLocalizedQuery()->findOneByFkLocale($idLocale);

        $this->assertStringContainsString($this->createUtilEncodingService()->encodeJson($data), $contentLocalized->getParameters());
    }

    /**
     * @param int $idLocale
     *
     * @return void
     */
    public function assertContentLocalizedDoesNotExist(int $idLocale): void
    {
        $contentLocalized = $this->getContentLocalizedQuery()->findOneByFkLocale($idLocale);

        $this->assertNull($contentLocalized);
    }

    /**
     * @param string|null $contentKey
     *
     * @return void
     */
    public function assertDatabaseTableContainsData(?string $contentKey = null): void
    {
        $contentQuery = $this->getContentQuery();
        $contentLocalizedQuery = $this->getContentLocalizedQuery();

        if ($contentKey) {
            $contentQuery = $contentQuery->findByKey($contentKey);
            $contentLocalizedQuery = $contentLocalizedQuery->findByFkContent($contentQuery->getFirst()->getIdContent());
        }

        $this->assertTrue(($contentQuery->count() > 0), 'Expected at least one entry in the database spy_content table but database table is empty.');
        $this->assertTrue(($contentLocalizedQuery->count() > 0), 'Expected at least one entry in the database spy_content_localized table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentQuery
     */
    protected function getContentQuery(): SpyContentQuery
    {
        return SpyContentQuery::create();
    }

    /**
     * @return \Orm\Zed\Content\Persistence\SpyContentLocalizedQuery
     */
    protected function getContentLocalizedQuery(): SpyContentLocalizedQuery
    {
        return SpyContentLocalizedQuery::create();
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function createUtilEncodingService(): UtilEncodingServiceInterface
    {
        if (empty($this->utilEncodingService)) {
            $this->utilEncodingService = new UtilEncodingService();
        }

        return $this->utilEncodingService;
    }
}
