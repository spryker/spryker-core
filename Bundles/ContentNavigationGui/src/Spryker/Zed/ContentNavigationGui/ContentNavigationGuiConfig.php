<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ContentNavigationGui\ContentNavigationGuiConfig getSharedConfig()
 */
class ContentNavigationGuiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string[]
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
