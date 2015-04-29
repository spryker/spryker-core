<?php

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use SprykerFeature\Zed\SearchPage\Persistence\Propel\SpySearchPageElementTemplate;

interface TemplateReaderInterface
{
    /**
     * @param $idTemplate
     *
     * @return SpySearchPageElementTemplate
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
