<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotDataImport\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsSlotTemplateBuilder;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplate;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery;

class CmsSlotDataImportHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer
     */
    public function hasCmsSlotTemplate(array $seedData = []): CmsSlotTemplateTransfer
    {
        $this->ensureSpyCmsSlotTemplateTableIsEmpty();

        $cmsSlotTemplateTransfer = (new CmsSlotTemplateBuilder($seedData))->build();
        $cmsSlotTemplateTransfer->setIdCmsSlotTemplate(null);

        $cmsSlotTemplateEntity = new SpyCmsSlotTemplate();
        $cmsSlotTemplateEntity->fromArray($cmsSlotTemplateTransfer->toArray());
        $cmsSlotTemplateEntity->save();

        $cmsSlotTemplateTransfer->fromArray($cmsSlotTemplateEntity->toArray());

        return $cmsSlotTemplateTransfer;
    }

    /**
     * @return void
     */
    public function ensureSpyCmsSlotTableIsEmpty(): void
    {
        $cmsSlotToCmsSlotTemplateQuery = $this->getCmsSlotToCmsSlotTemplateQuery();
        $cmsSlotToCmsSlotTemplateQuery->deleteAll();

        $cmsSlotQuery = $this->getCmsSlotQuery();
        $cmsSlotQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureSpyCmsSlotTemplateTableIsEmpty(): void
    {
        $cmsSlotToCmsSlotTemplateQuery = $this->getCmsSlotToCmsSlotTemplateQuery();
        $cmsSlotToCmsSlotTemplateQuery->deleteAll();

        $cmsSlotTemplateQuery = $this->getCmsSlotTemplateQuery();
        $cmsSlotTemplateQuery->deleteAll();
    }

    /**
     * @return void
     */
    public function assertSpyCmsSlotTableContainsData(): void
    {
        $cmsSlotQuery = $this->getCmsSlotQuery();
        $this->assertTrue($cmsSlotQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return void
     */
    public function assertSpyCmsSlotTemplateTableContainsData(): void
    {
        $cmsSlotTemplateQuery = $this->getCmsSlotTemplateQuery();
        $this->assertTrue($cmsSlotTemplateQuery->exists(), 'Expected at least one entry in the database table but database table is empty.');
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery
     */
    protected function getCmsSlotQuery(): SpyCmsSlotQuery
    {
        return SpyCmsSlotQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery
     */
    protected function getCmsSlotTemplateQuery(): SpyCmsSlotTemplateQuery
    {
        return SpyCmsSlotTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery
     */
    protected function getCmsSlotToCmsSlotTemplateQuery(): SpyCmsSlotToCmsSlotTemplateQuery
    {
        return SpyCmsSlotToCmsSlotTemplateQuery::create();
    }
}
