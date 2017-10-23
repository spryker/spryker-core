<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder;

interface TemplateNameBuilderInterface
{
    /**
     * Extracts the template name from the given path for a given application.
     *
     * @param string $filePath
     *
     * @return string
     */
    public function buildTemplateName($filePath);
}
