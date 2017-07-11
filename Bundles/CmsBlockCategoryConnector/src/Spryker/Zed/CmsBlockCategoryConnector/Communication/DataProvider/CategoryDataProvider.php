<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CategoryTransfer;
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
     * @var bool
     */
    protected $isTemplateSupported = true;

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
            CategoryType::OPTION_IS_TEMPLATE_SUPPORTED => $this->isTemplateSupported(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function getData(CategoryTransfer $categoryTransfer)
    {
        $this->assertTemplate($categoryTransfer);

        $idCmsBlocks = [];
        if ($categoryTransfer->getIdCategory()) {
            $idCmsBlocks = $this->getAssignedIdCmsBlocks(
                $categoryTransfer->getIdCategory(),
                $categoryTransfer->getFkCategoryTemplate()
            );
        }

        $categoryTransfer->setIdCmsBlocks($idCmsBlocks);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return array
     */
    protected function getAssignedIdCmsBlocks($idCategory, $idCategoryTemplate)
    {
        $query = $this->queryContainer
            ->queryCmsBlockCategoryWithBlocksByIdCategory($idCategory, $idCategoryTemplate)
            ->find();

        $assignedBlocks = [];

        foreach ($query as $item) {
            $assignedBlocks[$item->getFkCmsBlockCategoryPosition()][] = $item->getFkCmsBlock();
        }

        return $assignedBlocks;
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function assertTemplate(CategoryTransfer $categoryTransfer)
    {
        $spyCategoryTemplate = $this->categoryQueryContainer
            ->queryCategoryTemplateById($categoryTransfer->getFkCategoryTemplate())
            ->findOne();

        if (!$spyCategoryTemplate) {
            return;
        }

        if (!in_array($spyCategoryTemplate->getName(), CategoryType::SUPPORTED_CATEGORY_TEMPLATE_LIST)) {
            $this->isTemplateSupported = false;
        }
    }

    /**
     * @return bool
     */
    protected function isTemplateSupported()
    {
        return $this->isTemplateSupported;
    }

}
