<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder;

interface NamespacedTemplateNameBuilderInterface extends TemplateNameBuilderInterface
{
    /**
     * Extracts a namespaced template name from the given path for a given application.
     *
     * @param string $filePath
     *
     * @return string
     */
    public function buildNamespacedTemplateName(string $filePath): string;
}
