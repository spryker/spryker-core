<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface;

class TemplateNameBuilderYves implements TemplateNameBuilderInterface
{
    /**
     * @param string $filePath
     *
     * @return string
     */
    public function buildTemplateName($filePath)
    {
        $pathsParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $applicationPosition = array_search('Yves', $pathsParts);
        $bundle = $pathsParts[$applicationPosition + 1];
        $themePosition = array_search('Theme', $pathsParts);
        $template = array_slice($pathsParts, $themePosition + 2);

        return sprintf('@%s/%s', $bundle, implode('/', $template));
    }
}
