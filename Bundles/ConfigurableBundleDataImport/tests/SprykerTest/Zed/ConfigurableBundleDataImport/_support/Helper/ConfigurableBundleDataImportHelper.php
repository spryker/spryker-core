<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ConfigurableBundleDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;

class ConfigurableBundleDataImportHelper extends Module
{
    /**
     * @return void
     */
    public function assertConfigurableBundleTemplateDatabaseTablesContainsData(): void
    {
        $configurableBundleTemplateQuery = $this->getConfigurableBundleTemplateQuery();

        $this->assertTrue(
            $configurableBundleTemplateQuery->find()->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return void
     */
    public function assertConfigurableBundleTemplateSlotDatabaseTablesContainsData(): void
    {
        $configurableBundleTemplateSlotQuery = $this->getConfigurableBundleTemplateSlotQuery();

        $this->assertTrue(
            $configurableBundleTemplateSlotQuery->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return void
     */
    public function assertProductImageSetDatabaseTablesContainsData(): void
    {
        $productImageSetQuery = $this->getProductImageSetQuery();

        $this->assertTrue(
            $productImageSetQuery->count() > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function getConfigurableBundleTemplateQuery(): SpyConfigurableBundleTemplateQuery
    {
        return SpyConfigurableBundleTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    protected function getConfigurableBundleTemplateSlotQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return SpyConfigurableBundleTemplateSlotQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    protected function getProductImageSetQuery(): SpyProductImageSetQuery
    {
        return SpyProductImageSetQuery::create();
    }
}
