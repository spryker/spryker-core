<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ProductSearchBusiness;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Library\Storage\StorageInstanceBuilder;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\ProductSearch\Business\Internal\InstallProductSearch;
use SprykerFeature\Zed\ProductSearch\Business\Locator\OperationLocatorInterface;
use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;
use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationManagerInterface;
use SprykerFeature\Zed\ProductSearch\Business\Processor\ProductSearchProcessorInterface;
use SprykerFeature\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformerInterface;
use SprykerFeature\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use SprykerFeature\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;
use SprykerFeature\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerFeature\Zed\ProductSearch\ProductSearchConfig;

/**
 * @method ProductSearchBusiness getFactory()
 * @method ProductSearchConfig getConfig()
 */
class ProductSearchDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ProductAttributesTransformerInterface
     */
    public function getProductAttributesTransformer()
    {
        return $this->getFactory()->createTransformerProductAttributesTransformer(
            $this->createProductSearchQueryContainer(),
            $this->createOperationLocator(),
            $this->createDefaultOperation()
        );
    }

    /**
     * @return ProductSearchProcessorInterface
     */
    public function getProductSearchProcessor()
    {
        return $this->getFactory()->createProcessorProductSearchProcessor(
            $this->createKeyBuilder(),
            $this->getStoreName()
        );
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return InstallProductSearch
     */
    public function getInstaller(MessengerInterface $messenger)
    {
        $collectorFacade = $this->getLocator()->collector()->facade();

        $installer = $this->getFactory()->createInternalInstallProductSearch(
            StorageInstanceBuilder::getElasticsearchInstance(),
            $collectorFacade->getSearchIndexName(),
            $collectorFacade->getSearchDocumentType()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @return OperationInterface
     */
    protected function createDefaultOperation()
    {
        return $this->getFactory()->createOperationDefaultOperation();
    }

    /**
     * @return OperationLocatorInterface
     */
    protected function createOperationLocator()
    {
        $locator = $this->getFactory()->createLocatorOperationLocator();
        $config = $this->getConfig();

        foreach ($config->getPossibleOperations() as $operation) {
            $locator->addOperation($operation);
        }

        return $locator;
    }

    /**
     * @return OperationManagerInterface
     */
    protected function createOperationManager()
    {
        return $this->getFactory()->createOperationOperationManager(
            $this->createProductSearchQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return ProductSearchQueryContainerInterface
     */
    protected function createProductSearchQueryContainer()
    {
        return $this->getLocator()->productSearch()->queryContainer();
    }

    /**
     * @return ProductFacade
     */
    protected function createProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return ProductSearchToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return ProductSearchToTouchInterface
     */
    protected function createTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return KeyBuilderInterface
     */
    public function createKeyBuilder()
    {
        return $this->getFactory()->createBuilderProductResourceKeyBuilder();
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

}
