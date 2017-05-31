<?php


namespace Spryker\Zed\CmsBlock\Business;


use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 */
class CmsBlockBusinessFactory extends AbstractBusinessFactory
{
    public function createCmsBlockReader()
    {
        return new CmsBlockReader(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper()
        );
    }

    public function createCmsBlockMapper()
    {
        return new CmsBlockMapper();
    }
}