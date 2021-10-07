<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

interface TemplateReaderInterface
{
    /**
     * @param string $templatePath
     *
     * @return array<string>
     */
    public function getPlaceholdersByTemplatePath(string $templatePath): array;
}
