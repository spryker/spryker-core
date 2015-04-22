<?php

namespace SprykerFeature\Zed\Product\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductBusiness;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
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

/**
 * @method ProductBusiness getFactory()
 */
class ProductDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return UploadedFileImporter
     */
    public function createHttpFileImporter()
    {
        return $this->getFactory()->createImporterUploadUploadedFileImporter(
            $this->createSettings()->getDestinationDirectoryForUploads()
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
            $this->createProductBuilder(),
            $this->createProductWriter(),
            $this->createProductBatchResult()
        );

        return $importer;
    }

    /**
     * @return ProductSettings
     */
    protected function createSettings()
    {
        return $this->getFactory()->createProductSettings();
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
    protected function createProductBuilder()
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
            $this->createSettings()->getProductDefaultLocale()
        );
    }

    /**
     * @return ConcreteProductWriterInterface
     */
    protected function createConcreteProductWriter()
    {
        return $this->getFactory()->createImporterWriterDbConcreteProductWriter(
            $this->createSettings()->getProductDefaultLocale()
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
     * @return ProductQueryContainerInterface
     */
    protected function createProductQueryContainer()
    {
        return $this->getLocator()->product()->queryContainer();
    }

    /**
     * @param MessengerInterface $messenger
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
            $this->createProductQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return ProductManagerInterface
     */
    public function createProductManager()
    {
        return $this->getFactory()->createProductProductManager(
            $this->createProductQueryContainer(),
            $this->createTouchFacade(),
            $this->createUrlFacade(),
            $this->getLocator()
        );
    }

    /**
     * @return ProductToLocaleInterface
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @return ProductToTouchInterface
     */
    protected function createTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return ProductToUrlInterface
     */
    protected function createUrlFacade()
    {
        return $this->getLocator()->url()->facade();
    }
}
