<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;

use DateTime;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CategoryDataProvider
{
    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @var array
     */
    protected $wrongCmsBlocks = [];

    /**
     * @var array
     */
    protected $assignedCmsBlocksForTemplates = [];

    /**
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct(
        CmsBlockCategoryConnectorQueryContainerInterface $cmsBlockCategoryConnectorQueryContainer,
        CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockCategoryConnectorToCategoryQueryContainerInterface $categoryQueryContainer
    ) {
        $this->queryContainer = $cmsBlockCategoryConnectorQueryContainer;
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CategoryTransfer::class,
            CategoryType::OPTION_CMS_BLOCK_LIST => $this->getCmsBlockList(),
            CategoryType::OPTION_CMS_BLOCK_POSITION_LIST => $this->getPositionList(),
            CategoryType::OPTION_WRONG_CMS_BLOCK_LIST => $this->getWrongCmsBlockList(),
            CategoryType::OPTION_ASSIGNED_CMS_BLOCK_TEMPLATE_LIST => $this->getAssignedIdCmsBlocksForTemplates(),
            CategoryType::OPTION_CATEGORY_TEMPLATES => $this->getCategoryTemplates(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData(CategoryTransfer $categoryTransfer)
    {
        $this->populateAssignedCmsBlocksForTemplates($categoryTransfer);

        $idCmsBlocks = [];
        if ($categoryTransfer->getIdCategory()) {
            $idCmsBlocks = $this->getAssignedIdCmsBlocksByIdCategory($categoryTransfer->getIdCategory());
        }

        $categoryTransfer->setIdCmsBlocks($idCmsBlocks);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    protected function getAssignedIdCmsBlocksByIdCategory(int $idCategory): array
    {
        $cmsBlockCategoryEntities = $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategory($idCategory)
            ->find();

        $assignedBlocks = [];

        foreach ($cmsBlockCategoryEntities as $cmsBlockCategoryEntity) {
            $assignedBlocks[$cmsBlockCategoryEntity->getFkCategoryTemplate()][$cmsBlockCategoryEntity->getFkCmsBlockCategoryPosition()][] = $cmsBlockCategoryEntity->getFkCmsBlock();
            $this->assertCmsBlock($cmsBlockCategoryEntity->getCmsBlock());
        }

        return $assignedBlocks;
    }

    /**
     * @return array
     */
    protected function getAssignedIdCmsBlocksForTemplates()
    {
        return $this->assignedCmsBlocksForTemplates;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function populateAssignedCmsBlocksForTemplates(CategoryTransfer $categoryTransfer)
    {
        if (!$categoryTransfer->getIdCategory()) {
            return;
        }

        $query = $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategory($categoryTransfer->getIdCategory());

        $assignedBlocksForTemplates = [];
        foreach ($query->find() as $relation) {
            $idCmsBlockCategoryPosition = $relation->getFkCmsBlockCategoryPosition();
            $idCategoryTemplate = $relation->getFkCategoryTemplate();

            $assignedBlocksForTemplates[$idCmsBlockCategoryPosition][$idCategoryTemplate][] = $relation->getFkCmsBlock();
        }

        $this->assignedCmsBlocksForTemplates = $assignedBlocksForTemplates;
    }

    /**
     * @return array
     */
    protected function getPositionList()
    {
        return $this->queryContainer
            ->queryCmsBlockCategoryPosition()
            ->orderByIdCmsBlockCategoryPosition()
            ->find()
            ->toKeyValue('idCmsBlockCategoryPosition', 'name');
    }

    /**
     * @return array
     */
    protected function getCmsBlockList()
    {
        return $this->cmsBlockQueryContainer
            ->queryCmsBlockWithTemplate()
            ->find()
            ->toKeyValue('idCmsBlock', 'name');
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return void
     */
    protected function assertCmsBlock(SpyCmsBlock $spyCmsBlock)
    {
        $invalid = false;

        if (!$spyCmsBlock->getIsActive()) {
            $invalid = true;
        }

        $now = new DateTime();
        if ($spyCmsBlock->getValidFrom() && $spyCmsBlock->getValidFrom() > $now) {
            $invalid = true;
        }

        if ($spyCmsBlock->getValidTo() && $spyCmsBlock->getValidTo() < $now) {
            $invalid = true;
        }

        if ($invalid) {
            $this->wrongCmsBlocks[$spyCmsBlock->getIdCmsBlock()] = $spyCmsBlock->getName();
        }
    }

    /**
     * @return array
     */
    protected function getWrongCmsBlockList()
    {
        return $this->wrongCmsBlocks;
    }

    /**
     * @return array
     */
    protected function getCategoryTemplates(): array
    {
        return $this->categoryQueryContainer
            ->queryCategoryTemplate()
            ->find()
            ->toKeyValue(CategoryTemplateTransfer::ID_CATEGORY_TEMPLATE, CategoryTemplateTransfer::NAME);
    }
}
