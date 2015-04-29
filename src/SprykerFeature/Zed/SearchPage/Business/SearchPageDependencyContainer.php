<?php

namespace SprykerFeature\Zed\SearchPage\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPageBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\SearchPage\Business\Installer\TemplateInstaller;
use SprykerFeature\Zed\SearchPage\Business\Reader\DocumentAttributeReader;
use SprykerFeature\Zed\SearchPage\Business\Reader\PageElementReader;
use SprykerFeature\Zed\SearchPage\Business\Reader\TemplateReader;
use SprykerFeature\Zed\SearchPage\Business\Writer\DocumentAttributeWriter;
use SprykerFeature\Zed\SearchPage\Business\Writer\TemplateWriter;
use SprykerFeature\Zed\SearchPage\Business\Writer\PageElementWriter;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;

/**
 * @method SearchPageBusiness getFactory()
 */
class SearchPageDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return PageElementWriter
     */
    public function createPageElementWriter()
    {
        return $this->getFactory()->createWriterPageElementWriter(
            $this->createPageElementReader()
        );
    }

    /**
     * @return TemplateInstaller
     */
    public function getDocumentAttributeInstaller()
    {
        return $this->getFactory()->createInstallerDocumentAttributeInstaller(
            $this->createDocumentAttributeWriter(),
            $this->createDocumentAttributeReader(),
            $this->getLocator()
        );
    }

    /**
     * @return TemplateInstaller
     */
    public function getTemplateInstaller()
    {
        return $this->getFactory()->createInstallerTemplateInstaller(
            $this->createTemplateWriter(),
            $this->createTemplateReader(),
            $this->getLocator()
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
    private function getQueryContainer()
    {
        return $this->getLocator()->searchPage()->queryContainer();
    }
}
