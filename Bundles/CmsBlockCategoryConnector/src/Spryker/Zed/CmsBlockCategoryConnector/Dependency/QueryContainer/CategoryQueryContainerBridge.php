<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;


class CategoryQueryContainerBridge implements CategoryQueryContainerInterface
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

}