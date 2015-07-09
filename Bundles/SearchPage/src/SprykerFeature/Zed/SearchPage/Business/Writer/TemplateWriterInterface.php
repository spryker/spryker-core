<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Business\Writer;

use SprykerFeature\Shared\SearchPage\Dependency\TemplateInterface;

interface TemplateWriterInterface
{

    /**
     * @param TemplateInterface $template
     *
     * @return int
     */
    public function createTemplate(TemplateInterface $template);

    /**
     * @param TemplateInterface $template
     *
     * @return int
     */
    public function updateTemplate(TemplateInterface $template);

}
