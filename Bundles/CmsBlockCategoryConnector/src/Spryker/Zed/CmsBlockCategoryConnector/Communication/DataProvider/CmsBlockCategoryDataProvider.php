<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockCategoryType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;

class CmsBlockCategoryDataProvider
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
            CmsBlockCategoryType::OPTION_CATEGORY_ARRAY => $this->getCategoryList(),
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
        return $this->cmsBlockCategoryConnectorQueryContainer
            ->queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues('fkCategory');
    }

    /**
     * @return array
     */
    protected function getCategoryList()
    {
        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $categoryCollection = $this->categoryQueryContainer
            ->queryCategory($idLocale)
            ->find();

        $categoryList = [];

        /** @var \Orm\Zed\Category\Persistence\SpyCategory $spyCategory */
        foreach ($categoryCollection as $spyCategory) {
            $categoryName = $spyCategory->getLocalisedAttributes($idLocale)->getFirst()->getName();
            $categoryList[$spyCategory->getIdCategory()] = $categoryName;
        }

        return $categoryList;
    }

}
