<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockDataProvider
{
    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $cmsBlockCategoryConnectorQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var array
     */
    protected $idCategoriesWithWrongTemplate = [];

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface $localeFacade
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer,
        CmsBlockCategoryConnectorToLocaleInterface $localeFacade
    ) {
        $this->categoryQueryContainer = $categoryQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->cmsBlockCategoryConnectorQueryContainer = $cmsBlockCategoryConnectorQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsBlockTransfer::class,
            CmsBlockType::OPTION_CATEGORY_ARRAY => $this->getCategoryList(),
            CmsBlockType::OPTION_CMS_BLOCK_POSITION_LIST => $this->getPositionList(),
            CmsBlockType::OPTION_WRONG_TEMPLATE_CATEGORY_LIST => $this->getWrongTemplateCategoryList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function getData(CmsBlockTransfer $cmsBlockTransfer)
    {
        $categoryIds = [];

        if ($cmsBlockTransfer->getIdCmsBlock()) {
            $categoryIds = $this->getAssignedCategoryIds($cmsBlockTransfer->getIdCmsBlock());
        }

        $cmsBlockTransfer->setIdCategories($categoryIds);

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return array
     */
    protected function getAssignedCategoryIds($idCmsBlock)
    {
        $query = $this->cmsBlockCategoryConnectorQueryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
            ->find();

        $assignedIdCategories = [];

        foreach ($query as $item) {
            $assignedIdCategories[$item->getFkCmsBlockCategoryPosition()][] = $item->getFkCategory();
            $this->assertCmsBlockTemplate($item);
        }

        return $assignedIdCategories;
    }

    /**
     * @return array
     */
    protected function getWrongTemplateCategoryList()
    {
        return $this->idCategoriesWithWrongTemplate;
    }

    /**
     * @param \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector $cmsBlockCategoryConnectorEntity
     *
     * @return void
     */
    protected function assertCmsBlockTemplate(SpyCmsBlockCategoryConnector $cmsBlockCategoryConnectorEntity)
    {
        $categoryTemplateName = $this->getCategoryTemplateName($cmsBlockCategoryConnectorEntity->getCategory());

        if (!in_array($categoryTemplateName, CmsBlockType::SUPPORTED_CATEGORY_TEMPLATE_LIST)) {
            $this->idCategoriesWithWrongTemplate[$cmsBlockCategoryConnectorEntity->getFkCmsBlockCategoryPosition()][] =
                $cmsBlockCategoryConnectorEntity->getFkCategory();
        }
    }

    /**
     * @return array
     */
    protected function getCategoryList()
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        /** @var \Orm\Zed\Category\Persistence\SpyCategory[] $categoryCollection */
        $categoryCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        $categoryList = [];

        foreach ($categoryCollection as $categoryEntity) {
            $categoryName = $categoryEntity->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categoryTemplateName = $this->getCategoryTemplateName($categoryEntity);
            $categoryTemplateName = $categoryTemplateName ? ' [' . $categoryTemplateName . ']' : '';

            $categoryList[$categoryEntity->getIdCategory()] = $categoryName . $categoryTemplateName;
        }

        return $categoryList;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return string
     */
    protected function getCategoryTemplateName(SpyCategory $categoryEntity)
    {
        $categoryTemplateName = '';
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryTemplate|null $categoryTemplateEntity */
        $categoryTemplateEntity = $categoryEntity->getCategoryTemplate();

        if ($categoryTemplateEntity) {
            $categoryTemplateName = $categoryEntity->getCategoryTemplate()->getName();
        }

        return $categoryTemplateName;
    }

    /**
     * @return array
     */
    protected function getPositionList()
    {
        return $this->cmsBlockCategoryConnectorQueryContainer
            ->queryCmsBlockCategoryPosition()
            ->orderByIdCmsBlockCategoryPosition()
            ->find()
            ->toKeyValue('idCmsBlockCategoryPosition', 'name');
    }
}
