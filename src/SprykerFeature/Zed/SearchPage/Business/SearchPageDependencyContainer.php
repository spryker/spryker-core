<?php

namespace SprykerFeature\SearchPage\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\SearchPage\Business\Writer\PageAttributeWriter;

/**
 * @method SearchPageBusiness getFactory()
 */
class SearchPageDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return PageAttributeWriter
     */
    public function createPageAttributeWriter()
    {
        return $this->getFactory()->createPageAttributeWriter();
    }

    /**
     * @return PageAttributeTypeWriter
     */
    public function createPageAttributeTemplateWriter()
    {
        return $this->getFactory()->createPageAttributeTemplateWriter();
    }

    /**
     * @return DocumentAttributeWriter
     */
    public function createDocumentAttributeWriter()
    {
        return $this->getFactory()->createDocumentAttributeWriter();
    }
}
