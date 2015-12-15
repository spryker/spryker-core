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
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\ProductSearch\Business\Internal\InstallProductSearch;
use Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface;
use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;
use Spryker\Zed\ProductSearch\Business\Operation\OperationManagerInterface;
use Spryker\Zed\ProductSearch\Business\Processor\ProductSearchProcessorInterface;
use Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformerInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearch\Business\Operation\AddToResult;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToField;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method ProductSearchConfig getConfig()
 */
class ProductSearchDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ProductAttributesTransformerInterface
     */
    public function getProductAttributesTransformer()
    {
        return new ProductAttributesTransformer(
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
        return new ProductSearchProcessor(
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
        $collectorFacade = $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_COLLECTOR);

        $installer = new InstallProductSearch(
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
        return new DefaultOperation();
    }

    /**
     * @return OperationLocatorInterface
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
     * @return OperationManagerInterface
     */
    protected function createOperationManager()
    {
        return new OperationManager(
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
     * @return array|OperationInterface[]
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
     * @return AddToResult
     */
    protected function createAddToResult() {
        return new AddToResult();
    }

    /**
     * @return CopyToField
     */
    protected function createCopyToField() {
        return new CopyToField();
    }

    /**
     * @return CopyToFacet
     */
    protected function createCopyToFacet() {
        return new CopyToFacet();
    }

    /**
     * @return CopyToMultiField
     */
    protected function createCopyToMultiField() {
        return new CopyToMultiField();
    }

}
