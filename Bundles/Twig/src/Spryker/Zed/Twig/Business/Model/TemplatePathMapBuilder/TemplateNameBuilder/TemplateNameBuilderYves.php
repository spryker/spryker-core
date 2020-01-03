<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\NamespacedTemplateNameBuilderInterface;

class TemplateNameBuilderYves implements NamespacedTemplateNameBuilderInterface
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
        $module = $pathsParts[$applicationPosition + 1];
        $themePosition = array_search('Theme', $pathsParts);
        $template = array_slice($pathsParts, $themePosition + 2);

        return sprintf('@%s/%s', $module, implode('/', $template));
    }

    /**
     * @param string $filePath
     *
     * @return string
     */
    public function buildNamespacedTemplateName(string $filePath): string
    {
        $pathsParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $applicationPosition = array_search('Yves', $pathsParts);
        $organization = $pathsParts[$applicationPosition - 1];
        $module = $pathsParts[$applicationPosition + 1];
        $themePosition = array_search('Theme', $pathsParts);
        $template = array_slice($pathsParts, $themePosition + 2);

        return sprintf('@%s:%s/%s', $organization, $module, implode('/', $template));
    }
}
