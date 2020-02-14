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
        $template = $this->getTemplate($pathsParts);

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
        $template = $this->getTemplate($pathsParts);

        return sprintf('@%s:%s/%s', $organization, $module, implode('/', $template));
    }

    /**
     * @param string[] $pathsParts
     *
     * @return string[]
     */
    protected function getTemplate(array $pathsParts): array
    {
        $themePosition = array_search('Theme', $pathsParts);

        if ($themePosition) {
            return array_slice($pathsParts, $themePosition + 2);
        }

        $presentationLayerPosition = array_search('Presentation', $pathsParts);

        return array_slice($pathsParts, $presentationLayerPosition + 1);
    }
}
