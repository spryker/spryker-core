<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider\CmsSlotBlock;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageSlotBlockConditionForm;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsPageSlotBlockFormDataProvider implements CmsPageSlotBlockFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CmsSlotBlockTransfer::class,
            CmsPageSlotBlockConditionForm::OPTION_PAGES => $this->getPages(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getPages(): array
    {
        $cmsPageEntities = $this->cmsQueryContainer->queryLocalizedPagesWithTemplates()->find();

        return $this->mapCmsPageEntitiesToArray($cmsPageEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntities
     *
     * @return int[]
     */
    protected function mapCmsPageEntitiesToArray(ObjectCollection $cmsPageEntities): array
    {
        $pages = [];

        foreach ($cmsPageEntities as $cmsPageEntity) {
            $cmsPageName = explode(',', $cmsPageEntity->getName());
            $pages[$cmsPageName[0]] = $cmsPageEntity->getIdCmsPage();
        }

        return $pages;
    }
}
