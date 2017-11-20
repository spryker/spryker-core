<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Model\TemplateNameExtractor;

use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor as SharedTemplateNameExtractor;

class TemplateNameExtractor extends SharedTemplateNameExtractor
{
    /**
     * @param string $templatePath
     *
     * @return string
     */
    protected function filterTemplatePath($templatePath)
    {
        $templatePathParts = explode('/', $templatePath);

        $firstDirectory = array_shift($templatePathParts);
        $firstDirectory = $this->utilTextService->camelCaseToDash($firstDirectory);
        array_unshift($templatePathParts, $firstDirectory);

        $templateName = array_pop($templatePathParts);
        $templateName = $this->utilTextService->camelCaseToDash($templateName);

        array_push($templatePathParts, $templateName);
        $templatePath = implode('/', $templatePathParts);

        return $templatePath;
    }
}
