<?php


namespace Spryker\Zed\CmsBlockGui\Dependency\QueryContainer;


use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockGuiToCmsBlockQueryContainerBridge implements CmsBlockGuiToCmsBlockQueryContainerInterface
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
     * @param string $name
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockByName($name)
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockByName($name);
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function queryCmsBlockWithTemplate()
    {
        return $this->cmsBlockQueryContainer->queryCmsBlockWithTemplate();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function queryTemplates()
    {
        return $this->cmsBlockQueryContainer->queryTemplates();
    }

}