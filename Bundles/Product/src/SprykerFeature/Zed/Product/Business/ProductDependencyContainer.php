<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business;

use Generated\Shared\Locale\LocaleInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Product\Business\Attribute\AttributeManagerInterface;
use SprykerFeature\Zed\Product\Business\Builder\ProductBuilderInterface;
use SprykerFeature\Zed\Product\Business\Importer\FileImporter;
use SprykerFeature\Zed\Product\Business\Importer\Reader\File\IteratorReaderInterface;
use SprykerFeature\Zed\Product\Business\Importer\Upload\UploadedFileImporter;
use SprykerFeature\Zed\Product\Business\Importer\Validator\ImportProductValidator;
use SprykerFeature\Zed\Product\Business\Importer\Writer\AbstractProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ConcreteProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Importer\Writer\ProductWriterInterface;
use SprykerFeature\Zed\Product\Business\Internal\Install;
use SprykerFeature\Zed\Product\Business\Model\ProductBatchResultInterface;
use SprykerFeature\Zed\Product\Business\Product\ProductManagerInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use SprykerFeature\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerFeature\Zed\Product\ProductConfig;
use SprykerFeature\Zed\Product\ProductDependencyProvider;

use SprykerFeature\Zed\Product\Business\Product\ProductManager;

/**
 * @method ProductBusiness getFactory()
 * @method ProductConfig getConfig()
 * @method ProductQueryContainerInterface getQueryContainer()
 */
class ProductDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @var ProductManager
     */
    protected $productManager;

    /**
     * @return UploadedFileImporter
     */
    public function createHttpFileImporter()
    {
        return $this->getFactory()->createImporterUploadUploadedFileImporter(
            $this->getConfig()->getDestinationDirectoryForUploads()
        );
    }

    /**
     * @return FileImporter
     */
    public function createProductImporter()
    {
        $importer = $this->getFactory()->createImporterFileImporter(
            $this->createImportProductValidator(),
            $this->createCSVReader(),
            $this->createImportProductBuilder(),
            $this->createProductWriter(),
            $this->createProductBatchResult()
        );

        return $importer;
    }

    /**
     * @return ImportProductValidator
     */
    protected function createImportProductValidator()
    {
        return $this->getFactory()->createImporterValidatorImportProductValidator();
    }

    /**
     * @return IteratorReaderInterface
     */
    protected function createCSVReader()
    {
        return $this->getFactory()->createImporterReaderFileCsvReader();
    }

    /**
     * @return ProductBuilderInterface
     */
    protected function createImportProductBuilder()
    {
        return $this->getFactory()->createImporterBuilderProductBuilder();
    }

    /**
     * @return ProductWriterInterface
     */
    protected function createProductWriter()
    {
        return $this->getFactory()->createImporterWriterProductWriter(
            $this->createAbstractProductWriter(),
            $this->createConcreteProductWriter()
        );
    }

    /**
     * @return AbstractProductWriterInterface
     */
    protected function createAbstractProductWriter()
    {
        return $this->getFactory()->createImporterWriterDbAbstractProductWriter(
            $this->getCurrentLocale()
        );
    }

    /**
     * @return ConcreteProductWriterInterface
     */
    protected function createConcreteProductWriter()
    {
        return $this->getFactory()->createImporterWriterDbConcreteProductWriter(
            $this->getCurrentLocale()
        );
    }

    /**
     * @return ProductBatchResultInterface
     */
    protected function createProductBatchResult()
    {
        return $this->getFactory()->createModelProductBatchResult();
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return Install
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = $this->getFactory()->createInternalInstall(
            $this->createAttributeManager()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @return AttributeManagerInterface
     */
    public function createAttributeManager()
    {
        return $this->getFactory()->createAttributeAttributeManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ProductManagerInterface
     */
    public function createProductManager()
    {
        if (null === $this->productManager) {
            $this->productManager = $this->getFactory()->createProductProductManager(
                $this->getQueryContainer(),
                $this->getTouchFacade(),
                $this->getUrlFacade(),
                $this->getLocaleFacade()
            );
        }

        return $this->productManager;
    }

    /**
     * @return ProductToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return ProductToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return ProductToUrlInterface
     */
    protected function getUrlFacade()
    {
        return  $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return LocaleInterface
     */
    protected function getCurrentLocale()
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

}
