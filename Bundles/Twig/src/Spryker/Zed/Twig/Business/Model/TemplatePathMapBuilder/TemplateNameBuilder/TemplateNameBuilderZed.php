<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\NamespacedTemplateNameBuilderInterface;

class TemplateNameBuilderZed implements NamespacedTemplateNameBuilderInterface
{
    /**
     * @param string $filePath
     *
     * @return string
     */
    public function buildTemplateName($filePath)
    {
        $pathsParts = explode(DIRECTORY_SEPARATOR, $filePath);
        $applicationPosition = array_search('Zed', $pathsParts);
        $module = $pathsParts[$applicationPosition + 1];
        $presentationLayerPosition = array_search('Presentation', $pathsParts);
        $template = array_slice($pathsParts, $presentationLayerPosition + 1);

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
        $applicationPosition = array_search('Zed', $pathsParts);
        $module = $pathsParts[$applicationPosition + 1];
        $organization = $pathsParts[$applicationPosition - 1];
        $presentationLayerPosition = array_search('Presentation', $pathsParts);
        $template = array_slice($pathsParts, $presentationLayerPosition + 1);

        return sprintf('@%s:%s/%s', $organization, $module, implode('/', $template));
    }
}
