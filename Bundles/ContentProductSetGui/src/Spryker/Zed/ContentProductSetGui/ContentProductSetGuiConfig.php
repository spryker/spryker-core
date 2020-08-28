<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ContentProductSetGui\ContentProductSetGuiConfig getSharedConfig()
 */
class ContentProductSetGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return array
     */
    public function getContentWidgetTemplates(): array
    {
        return $this->getSharedConfig()->getContentWidgetTemplates();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return $this->getSharedConfig()->getTwigFunctionName();
    }
}
