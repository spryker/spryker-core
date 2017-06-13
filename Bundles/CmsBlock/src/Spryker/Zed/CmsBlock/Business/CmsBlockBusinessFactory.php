<?php


namespace Spryker\Zed\CmsBlock\Business;


use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGenerator;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryManagerInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryWriterInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReader;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockReaderInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManager;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateManagerInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapper;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriter;
use Spryker\Zed\CmsBlock\Business\Model\CmsBlockWriterInterface;
use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Finder\Finder;

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
     * @return CmsBlockTemplateManagerInterface
     */
    public function createCmsBlockTemplateManager()
    {
        return new CmsBlockTemplateManager(
            $this->getQueryContainer(),
            $this->createCmsBlockTemplateMapper(),
            $this->getConfig(),
            $this->createFinder()
        );
    }

    /**
     * @return CmsBlockGlossaryManagerInterface
     */
    public function createCmsBlockGlossaryManager()
    {
        return new CmsBlockGlossaryManager(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_LOCALE)
        );
    }

    /**
     * @return CmsBlockGlossaryWriterInterface
     */
    public function createCmsBlockGlossaryWriter()
    {
        return new CmsBlockGlossaryWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY),
            $this->createCmsBlockGlossaryKeyGenerator(),
            $this->getProvidedDependency(CmsBlockDependencyProvider::QUERY_CONTAINER_GLOSSARY)
        );
    }

    /**
     * @return Finder
     */
    protected function createFinder()
    {
        return new Finder();
    }

    /**
     * @return CmsBlockTemplateMapperInterface
     */
    protected function createCmsBlockTemplateMapper()
    {
        return new CmsBlockTemplateMapper();
    }

    /**
     * @return CmsBlockGlossaryKeyGeneratorInterface
     */
    protected function createCmsBlockGlossaryKeyGenerator()
    {
        return new CmsBlockGlossaryKeyGenerator(
            $this->getProvidedDependency(CmsBlockDependencyProvider::FACADE_GLOSSARY)
        );
    }

}