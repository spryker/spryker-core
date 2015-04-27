<?php

namespace SprykerFeature\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageAttributeTemplate;

interface PageAttributeTemplateReaderInterface
{

    /**
     * @param $idTemplate
     *
     * @return SpySearchPageAttributeTemplate
     */
    public function getTemplateById($idTemplate);

    /**
     * @param $templateName
     *
     * @return bool
     */
    public function hasTemplateByName($templateName);

    /**
     * @return bool
     */
    public function hasTemplates();
}
