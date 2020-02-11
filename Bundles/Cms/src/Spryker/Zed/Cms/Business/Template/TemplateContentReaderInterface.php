<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

interface TemplateContentReaderInterface
{
    /**
     * @param string $templatePath
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException
     *
     * @return string
     */
    public function getTemplateContent(string $templatePath): string;
}
