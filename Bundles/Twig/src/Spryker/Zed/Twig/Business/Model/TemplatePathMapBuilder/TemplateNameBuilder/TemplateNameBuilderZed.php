<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface;

class TemplateNameBuilderZed implements TemplateNameBuilderInterface
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
        $bundle = $pathsParts[$applicationPosition + 1];
        $presentationLayerPosition = array_search('Presentation', $pathsParts);
        $template = array_slice($pathsParts, $presentationLayerPosition + 1);

        return sprintf('@%s/%s', $bundle, implode('/', $template));
    }
}
