<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPageBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\SearchPage\Business\Installer\DocumentAttributeInstaller;
use SprykerFeature\Zed\SearchPage\Business\Installer\TemplateInstaller;
use SprykerFeature\Zed\SearchPage\Business\KeyBuilder\SearchPageConfigKeyBuilder;
use SprykerFeature\Zed\SearchPage\Business\Processor\SearchPageConfigProcessor;
use SprykerFeature\Zed\SearchPage\Business\Reader\DocumentAttributeReader;
use SprykerFeature\Zed\SearchPage\Business\Reader\PageElementReader;
use SprykerFeature\Zed\SearchPage\Business\Reader\TemplateReader;
use SprykerFeature\Zed\SearchPage\Business\Writer\DocumentAttributeWriter;
use SprykerFeature\Zed\SearchPage\Business\Writer\TemplateWriter;
use SprykerFeature\Zed\SearchPage\Business\Writer\PageElementWriter;
use SprykerFeature\Zed\SearchPage\Dependency\Facade\SearchPageToTouchInterface;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;

/**
 * @method SearchPageBusiness getFactory()
 */
class SearchPageDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return PageElementWriter
     */
    public function createPageElementWriter()
    {
        return $this->getFactory()->createWriterPageElementWriter(
            $this->createPageElementReader(),
            $this->createTouchFacade()
        );
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return DocumentAttributeInstaller
     */
    public function createDocumentAttributeInstaller(MessengerInterface $messenger)
    {
        $attributeInstaller = $this->getFactory()->createInstallerDocumentAttributeInstaller(
            $this->createDocumentAttributeWriter(),
            $this->createDocumentAttributeReader(),
            $this->getLocator()
        );

        return $attributeInstaller->setMessenger($messenger);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return TemplateInstaller
     */
    public function createTemplateInstaller(MessengerInterface $messenger)
    {
        $templateInstaller = $this->getFactory()->createInstallerTemplateInstaller(
            $this->createTemplateWriter(),
            $this->createTemplateReader(),
            $this->getLocator()
        );

        return $templateInstaller->setMessenger($messenger);
    }

    /**
     * @return SearchPageConfigProcessor
     */
    public function createSearchPageConfigProcessor()
    {
        return $this->getFactory()->createProcessorSearchPageConfigProcessor(
            $this->getSearchPageConfigKeyBuilder()
        );
    }

    /**
     * @return PageElementReader
     */
    private function createPageElementReader()
    {
        return $this->getFactory()->createReaderPageElementReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DocumentAttributeWriter
     */
    private function createDocumentAttributeWriter()
    {
        return $this->getFactory()->createWriterDocumentAttributeWriter(
            $this->createDocumentAttributeReader()
        );
    }

    /**
     * @return DocumentAttributeReader
     */
    private function createDocumentAttributeReader()
    {
        return $this->getFactory()->createReaderDocumentAttributeReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TemplateWriter
     */
    private function createTemplateWriter()
    {
        return $this->getFactory()->createWriterTemplateWriter(
            $this->createTemplateReader()
        );
    }

    /**
     * @return TemplateReader
     */
    private function createTemplateReader()
    {
        return $this->getFactory()->createReaderTemplateReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return SearchPageQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->searchPage()->queryContainer();
    }

    /**
     * @return SearchPageToTouchInterface
     */
    private function createTouchFacade()
    {
        return $this->getLocator()->touch()->facade();
    }

    /**
     * @return SearchPageConfigKeyBuilder
     */
    private function getSearchPageConfigKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderSearchPageConfigKeyBuilder();
    }

}
