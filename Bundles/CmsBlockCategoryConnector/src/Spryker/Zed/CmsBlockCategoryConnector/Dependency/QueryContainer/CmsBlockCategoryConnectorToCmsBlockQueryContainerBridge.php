<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;


use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockCategoryConnectorToCmsBlockQueryContainerBridge implements CmsBlockCategoryConnectorToCmsBlockQueryContainerInterface
{
    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct($cmsBlockQueryContainer)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate()
    {
        return $this->cmsBlockQueryContainer
            ->queryCmsBlockWithTemplate();
    }

}