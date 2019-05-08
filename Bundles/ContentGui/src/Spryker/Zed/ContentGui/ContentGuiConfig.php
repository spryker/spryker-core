<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentGuiConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getContentWidgetTemplatePath(): string
    {
        return $this->getModuleRoot()
            . DIRECTORY_SEPARATOR . 'Presentation/_template/_content_widget.twig';
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
