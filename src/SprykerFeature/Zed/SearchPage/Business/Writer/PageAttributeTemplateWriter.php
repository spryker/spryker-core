<?php

namespace SprykerFeature\SearchPage\Business\Writer;

use SprykerFeature\Shared\SearchPage\Dependency\PageAttributeTemplateInterface;
use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageAttributeTemplate;

class PageAttributeTemplateWriter implements PageAttributeTemplateWriterInterface
{

    /**
     * @param PageAttributeTemplateInterface $pageAttributeTemplate
     *
     * @return int
     */
    public function createPageAttributeTemplate(PageAttributeTemplateInterface $pageAttributeTemplate)
    {
        $templateEntity = new SpySearchPageAttributeTemplate();
        $templateEntity->setTypeName($pageAttributeTemplate->getTemplateName());
        $templateEntity->save();

        return $templateEntity->getPrimaryKey();
    }

    /**
     * @param PageAttributeTemplateInterface $pageAttributeTemplate
     *
     * @return int
     */
    public function updatePageAttributeTemplate(PageAttributeTemplateInterface $pageAttributeTemplate)
    {
        return 2;
    }
}
