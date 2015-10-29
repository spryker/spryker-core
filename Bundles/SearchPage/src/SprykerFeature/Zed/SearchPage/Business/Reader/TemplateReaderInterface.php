<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Reader;

use Orm\Zed\SearchPage\Persistence\SpySearchPageElementTemplate;

interface TemplateReaderInterface
{

    /**
     * @param int $idTemplate
     *
     * @return SpySearchPageElementTemplate
     */
    public function getTemplateById($idTemplate);

    /**
     * @param string $templateName
     *
     * @return bool
     */
    public function hasTemplateByName($templateName);

    /**
     * @return bool
     */
    public function hasTemplates();

}
