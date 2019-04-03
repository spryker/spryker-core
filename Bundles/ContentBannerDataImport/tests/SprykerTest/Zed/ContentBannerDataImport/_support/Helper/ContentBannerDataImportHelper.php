<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentBannerDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;

class ContentBannerDataImportHelper extends Module
{
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
     * @param int $locale
     * @param string $parameter
     * @param string $value
     *
     * @return void
     */
    public function assertContentLocalizedParameterHasValue(int $locale, string $parameter, string $value): void
    {
        $contentLocalized = $this->getContentLocalizedQuery()->findOneByFkLocale($locale);
        $parameters = json_decode($contentLocalized->getParameters(), true);

        $this->assertEquals($parameters[$parameter], $value);
    }

    /**
     * @param int $locale
     *
     * @return void
     */
    public function assertContentLocalizedDoesNotExist(int $locale): void
    {
        $contentLocalized = $this->getContentLocalizedQuery()->findOneByFkLocale($locale);

        $this->assertNull($contentLocalized);
    }

    /**
     * @return void
     */
    public function assertDatabaseTableContainsData(): void
    {
        $contentQuery = $this->getContentQuery();
        $this->assertTrue($contentQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
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
}
