<?php


namespace Spryker\Zed\CmsBlock\Business;


use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriterInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReaderInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriterInterface;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 */
class CmsBlockBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return CmsBlockReaderInterface
     */
    public function createCmsBlockReader()
    {
        return new CmsBlockReader(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper()
        );
    }

    /**
     * @return CmsBlockMapperInterface
     */
    public function createCmsBlockMapper()
    {
        return new CmsBlockMapper();
    }

    /**
     * @return CmsBlockWriterInterface
     */
    public function createCmsBlockWrite()
    {
        return new CmsBlockWriter(
            $this->getQueryContainer(),
            $this->createCmsBlockMapper(),
            $this->createCmsBlockGlossaryWriter(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH)
        );
    }

    /**
     * @return CmsBlockToTouchFacadeInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return CmsBlockGlossaryWriterInterface
     */
    protected function createCmsBlockGlossaryWriter()
    {
        return new CmsBlockGlossaryWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY)
        );
    }

}