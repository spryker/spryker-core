<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class GuiConfig extends AbstractBundleConfig
{
    protected const FORM_RESOURCES_PATH = '/Presentation/Form/Type';
    protected const FORM_DEFAULT_TEMPLATE_FILE_NAMES = [
        'form_div_layout.html.twig',
        'bootstrap_3_layout.html.twig',
    ];

    /**
     * @return string
     */
    public function getFormResourcesPath(): string
    {
        return __DIR__ . static::FORM_RESOURCES_PATH;
    }

    /**
     * @return string[]
     */
    public function getTemplatePaths(): array
    {
        return [
            $this->getFormResourcesPath(),
        ];
    }

    /**
     * @return string[]
     */
    public function getDefaultTemplateFileNames(): array
    {
        return static::FORM_DEFAULT_TEMPLATE_FILE_NAMES;
    }
}
