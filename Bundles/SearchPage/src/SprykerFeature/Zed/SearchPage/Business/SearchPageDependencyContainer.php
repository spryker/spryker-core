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
        return new PageElementWriter(
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
        $attributeInstaller = new DocumentAttributeInstaller(
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
        $templateInstaller = new TemplateInstaller(
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
        return new SearchPageConfigProcessor(
            $this->getSearchPageConfigKeyBuilder()
        );
    }

    /**
     * @return PageElementReader
     */
    private function createPageElementReader()
    {
        return new PageElementReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return DocumentAttributeWriter
     */
    private function createDocumentAttributeWriter()
    {
        return new DocumentAttributeWriter(
            $this->createDocumentAttributeReader()
        );
    }

    /**
     * @return DocumentAttributeReader
     */
    private function createDocumentAttributeReader()
    {
        return new DocumentAttributeReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TemplateWriter
     */
    private function createTemplateWriter()
    {
        return new TemplateWriter(
            $this->createTemplateReader()
        );
    }

    /**
     * @return TemplateReader
     */
    private function createTemplateReader()
    {
        return new TemplateReader(
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
        return new SearchPageConfigKeyBuilder();
    }

}
