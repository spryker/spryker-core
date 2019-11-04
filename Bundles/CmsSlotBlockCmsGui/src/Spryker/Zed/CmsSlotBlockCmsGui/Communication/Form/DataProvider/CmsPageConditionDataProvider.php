<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageConditionForm;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface;

class CmsPageConditionDataProvider implements CmsPageConditionDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsSlotBlockCmsGuiToCmsQueryContainerInterface $cmsQueryContainer)
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
            CmsPageConditionForm::OPTION_PAGE_IDS => $this->getPageIds(),
        ];
    }

    /**
     * @return int[]
     */
    protected function getPageIds(): array
    {
        $cmsPageEntityCollection = $this->cmsQueryContainer->queryLocalizedPagesWithTemplates()->find();

        return $this->getCmsPageIdsFromCollection($cmsPageEntityCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Cms\Persistence\SpyCmsPage[] $cmsPageEntityCollection
     *
     * @return int[]
     */
    protected function getCmsPageIdsFromCollection(ObjectCollection $cmsPageEntityCollection): array
    {
        $pageIds = [];

        foreach ($cmsPageEntityCollection as $cmsPageEntity) {
            $cmsPageName = explode(',', $cmsPageEntity->getName());
            $pageIds[$cmsPageName[0]] = $cmsPageEntity->getIdCmsPage();
        }

        return $pageIds;
    }
}
