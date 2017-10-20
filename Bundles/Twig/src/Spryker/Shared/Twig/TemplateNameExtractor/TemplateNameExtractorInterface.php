<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\TemplateNameExtractor;

interface TemplateNameExtractorInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function extractBundleName($name);

    /**
     * @param string $name
     *
     * @return string
     */
    public function extractTemplatePath($name);
}
