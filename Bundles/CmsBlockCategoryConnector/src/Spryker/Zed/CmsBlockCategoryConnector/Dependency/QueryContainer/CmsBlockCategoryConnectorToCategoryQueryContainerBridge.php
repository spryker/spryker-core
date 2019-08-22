<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;

class CmsBlockCategoryConnectorToCategoryQueryContainerBridge implements CmsBlockCategoryConnectorToCategoryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $categoryQueryContainer;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $categoryQueryContainer
     */
    public function __construct($categoryQueryContainer)
    {
        $this->categoryQueryContainer = $categoryQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategory($idLocale)
    {
        return $this->categoryQueryContainer->queryCategory($idLocale);
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function queryCategoryById($idCategory)
    {
        return $this->categoryQueryContainer->queryCategoryById($idCategory);
    }

    /**
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplateById($idCategoryTemplate)
    {
        return $this->categoryQueryContainer->queryCategoryTemplateById($idCategoryTemplate);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function queryCategoryTemplate()
    {
        return $this->categoryQueryContainer->queryCategoryTemplate();
    }
}
