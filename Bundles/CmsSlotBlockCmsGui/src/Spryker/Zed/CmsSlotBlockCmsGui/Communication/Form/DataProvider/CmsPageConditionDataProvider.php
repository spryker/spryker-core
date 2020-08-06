<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\DataProvider;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageSlotBlockConditionForm;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeInterface;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface;

class CmsPageConditionDataProvider implements CmsPageConditionDataProviderInterface
{
    protected const KEY_OPTION_ALL_CMS_PAGES = 'All CMS Pages';
    protected const KEY_OPTION_SPECIFIC_CMS_PAGES = 'Specific CMS Pages';

    /**
     * @uses \Spryker\Zed\Cms\Persistence\CmsQueryContainer::CMS_NAME
     */
    protected const CMS_PAGE_NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CmsSlotBlockCmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsSlotBlockCmsGuiToTranslatorFacadeInterface $translatorFacade,
        CmsSlotBlockCmsGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->translatorFacade = $translatorFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            CmsPageSlotBlockConditionForm::OPTION_ALL_ARRAY => $this->getAllOptions(),
            CmsPageSlotBlockConditionForm::OPTION_PAGE_ARRAY => $this->getPages(),
        ];
    }

    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        return [
            $this->translatorFacade->trans(static::KEY_OPTION_ALL_CMS_PAGES) => true,
            $this->translatorFacade->trans(static::KEY_OPTION_SPECIFIC_CMS_PAGES) => false,
        ];
    }

    /**
     * @return int[]
     */
    protected function getPages(): array
    {
        $cmsPageEntityCollection = $this->cmsQueryContainer
            ->queryPagesWithTemplatesForSelectedLocale($this->localeFacade->getCurrentLocale()->getIdLocale())
            ->find();

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
            $pageIds[$cmsPageEntity->getVirtualColumn(static::CMS_PAGE_NAME)] = $cmsPageEntity->getIdCmsPage();
        }

        return $pageIds;
    }
}
