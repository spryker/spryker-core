<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlot\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsSlotBuilder;
use Generated\Shared\DataBuilder\CmsSlotTemplateBuilder;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlot;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplate;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CmsSlotHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function haveCmsSlot(array $override = []): CmsSlotTransfer
    {
        $data = [
            CmsSlotTransfer::KEY => 'test-center',
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => 'SprykerTestBlock',
            CmsSlotTransfer::NAME => 'Test Name',
            CmsSlotTransfer::DESCRIPTION => 'Test description.',
            CmsSlotTransfer::IS_ACTIVE => true,
        ];

        $cmsSlotTransfer = (new CmsSlotBuilder(array_merge($data, $override)))->build();

        return $cmsSlotTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer
     */
    public function haveCmsSlotTemplate(array $override = []): CmsSlotTemplateTransfer
    {
        $data = [
            CmsSlotTemplateTransfer::PATH => '@TestModule/views/test/test.twig',
            CmsSlotTemplateTransfer::NAME => 'Test Name',
            CmsSlotTemplateTransfer::DESCRIPTION => 'Test description.',
        ];

        $cmsSlotTemplateTransfer = (new CmsSlotTemplateBuilder(array_merge($data, $override)))->build();

        return $cmsSlotTemplateTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function haveCmsSlotInDb(array $override = []): CmsSlotTransfer
    {
        $data = [
            CmsSlotTransfer::KEY => 'test-center',
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => 'SprykerCmsSlotBlock',
            CmsSlotTransfer::NAME => 'Test Name',
            CmsSlotTransfer::DESCRIPTION => 'Test description.',
            CmsSlotTransfer::IS_ACTIVE => 1,
        ];

        $cmsSlotTransfer = (new CmsSlotBuilder(array_merge($data, $override)))->build();

        $cmsSlotEntity = new SpyCmsSlot();
        $cmsSlotEntity->fromArray($cmsSlotTransfer->toArray());
        $cmsSlotEntity->save();

        $cmsSlotTransfer->setIdCmsSlot($cmsSlotEntity->getIdCmsSlot());

        return $cmsSlotTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer
     */
    public function haveCmsSlotTemplateInDb(array $override = []): CmsSlotTemplateTransfer
    {
        $data = [
            CmsSlotTemplateTransfer::PATH => '@TestModule/views/test/test.twig',
            CmsSlotTemplateTransfer::NAME => 'Test Name',
            CmsSlotTemplateTransfer::DESCRIPTION => 'Test description.',
        ];

        $cmsSlotTemplateTransfer = (new CmsSlotTemplateBuilder(array_merge($data, $override)))->build();

        $cmsSlotTemplateEntity = new SpyCmsSlotTemplate();
        $cmsSlotTemplateEntity->fromArray($cmsSlotTemplateTransfer->toArray());
        $cmsSlotTemplateEntity->setPathHash(md5($cmsSlotTemplateTransfer->getPath()));
        $cmsSlotTemplateEntity->save();

        $cmsSlotTemplateTransfer->setIdCmsSlotTemplate($cmsSlotTemplateEntity->getIdCmsSlotTemplate());

        return $cmsSlotTemplateTransfer;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return bool
     */
    public function isActiveCmsSlotById(int $idCmsSlot): bool
    {
        $cmsSlot = SpyCmsSlotQuery::create()->findOneByIdCmsSlot($idCmsSlot);

        return $cmsSlot->getIsActive();
    }

    /**
     * @return void
     */
    public function ensureCmsSlotTableIsEmpty(): void
    {
        $this->getCmsSlotToCmsSlotTemplateQuery()->deleteAll();
        $this->getCmsSlotQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureCmsSlotTemplateTableIsEmpty(): void
    {
        $this->getCmsSlotToCmsSlotTemplateQuery()->deleteAll();
        $this->getCmsSlotTemplateQuery()->deleteAll();
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
