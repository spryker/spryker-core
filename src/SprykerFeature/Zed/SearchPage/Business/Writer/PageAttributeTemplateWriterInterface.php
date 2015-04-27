<?php

namespace SprykerFeature\SearchPage\Business\Writer;

use SprykerFeature\Shared\SearchPage\Dependency\PageAttributeTemplateInterface;

interface PageAttributeTemplateWriterInterface
{
    /**
     * @param PageAttributeTemplateInterface $pageAttributeTemplate
     *
     * @return int
     */
    public function createPageAttributeTemplate(PageAttributeTemplateInterface $pageAttributeTemplate);

    /**
     * @param PageAttributeTemplateInterface $pageAttributeTemplate
     *
     * @return int
     */
    public function updatePageAttributeTemplate(PageAttributeTemplateInterface $pageAttributeTemplate);
}
