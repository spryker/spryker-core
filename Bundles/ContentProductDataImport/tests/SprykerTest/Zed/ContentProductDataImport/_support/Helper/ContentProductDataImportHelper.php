<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentProductDataImport\Helper;

use Codeception\Module;
use Orm\Zed\Content\Persistence\SpyContentLocalizedQuery;
use Orm\Zed\Content\Persistence\SpyContentQuery;

class ContentProductDataImportHelper extends Module
{
    private const FIELD_ID_PRODUCT_ABSTRACTS = 'id_product_abstracts';

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
     * @param array $products
     *
     * @return void
     */
    public function assertContentLocalizedHasProducts(int $locale, array $products): void
    {
        $contentLocalized = $this->getContentLocalizedQuery()->findOneByFkLocale($locale);
        $parameters = json_decode($contentLocalized->getParameters(), true);

        $this->assertEquals($parameters[static::FIELD_ID_PRODUCT_ABSTRACTS], $products);
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
        $this->assertTrue(($contentQuery->count() > 0), 'Expected at least one entry in the database spy_content table but database table is empty.');

        $contentLocalizedQuery = $this->getContentLocalizedQuery();
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
}
