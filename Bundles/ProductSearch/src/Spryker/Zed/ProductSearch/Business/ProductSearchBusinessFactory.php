<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Zed\ProductSearch\Business\Builder\ProductResourceKeyBuilder;
use Spryker\Zed\ProductSearch\Business\Operation\OperationManager;
use Spryker\Zed\ProductSearch\Business\Locator\OperationLocator;
use Spryker\Zed\ProductSearch\Business\Operation\DefaultOperation;
use Spryker\Zed\ProductSearch\Business\Processor\ProductSearchProcessor;
use Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\Storage\StorageInstanceBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Internal\InstallProductSearch;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearch\Business\Operation\AddToResult;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToField;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method ProductSearchConfig getConfig()
 * @method ProductSearchQueryContainer getQueryContainer()
 */
class ProductSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformerInterface
     */
    public function createProductAttributesTransformer()
    {
        return new ProductAttributesTransformer(
            $this->getQueryContainer(),
            $this->createOperationLocator(),
            $this->createDefaultOperation()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Processor\ProductSearchProcessorInterface
     */
    public function createProductSearchProcessor()
    {
        return new ProductSearchProcessor(
            $this->createKeyBuilder(),
            $this->getStoreName()
        );
    }

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return \Spryker\Zed\ProductSearch\Business\Internal\InstallProductSearch
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $collectorFacade = $this->getCollectorFacade();

        $installer = new InstallProductSearch(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $collectorFacade->getSearchIndexName(),
            $collectorFacade->getSearchDocumentType()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface
     */
    protected function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface
     */
    protected function createDefaultOperation()
    {
        return new DefaultOperation();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface
     */
    protected function createOperationLocator()
    {
        $locator = new OperationLocator();
        $operations = $this->getPossibleOperations();

        foreach ($operations as $operation) {
            $locator->addOperation($operation);
        }

        return $locator;
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationManagerInterface
     */
    protected function createOperationManager()
    {
        return new OperationManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @deprecated Use getQueryContainer() instead.
     *
     * @return \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected function createProductSearchQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getQueryContainer();
    }

    /**
     * @deprecated Use getQueryContainer() directly.
     *
     * @return \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected function getProductSearchQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() instead.', E_USER_DEPRECATED);

        return $this->getQueryContainer();
    }

    /**
     * @deprecated Use getLocaleFacade() instead.
     *
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        trigger_error('Deprecated, use getLocaleFacade() instead.', E_USER_DEPRECATED);

        return $this->getLocaleFacade();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @deprecated Use getTouchFacade() instead.
     *
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected function createTouchFacade()
    {
        trigger_error('Deprecated, use getTouchFacade() instead.', E_USER_DEPRECATED);

        return $this->getTouchFacade();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    public function createKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return array|\Spryker\Zed\ProductSearch\Business\Operation\OperationInterface[]
     */
    protected function getPossibleOperations()
    {
        return [
            $this->createAddToResult(),
            $this->createCopyToField(),
            $this->createCopyToFacet(),
            $this->createCopyToMultiField(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\AddToResult
     */
    protected function createAddToResult()
    {
        return new AddToResult();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToField
     */
    protected function createCopyToField()
    {
        return new CopyToField();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet
     */
    protected function createCopyToFacet()
    {
        return new CopyToFacet();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField
     */
    protected function createCopyToMultiField()
    {
        return new CopyToMultiField();
    }

}
