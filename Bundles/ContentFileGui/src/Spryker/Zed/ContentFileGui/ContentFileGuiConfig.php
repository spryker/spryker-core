<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ContentFileGui\ContentFileGuiConfig getSharedConfig()
 */
class ContentFileGuiConfig extends AbstractBundleConfig
{
    /**
     * Should be more than in max count of list files
     */
    public const MAX_NUMBER_SELECTABLE_FILES_IN_FILE_LIST = 30;

    /**
     * @return array
     */
    public function getContentWidgetTemplates(): array
    {
        return $this->getSharedConfig()->getContentWidgetTemplates();
    }

    /**
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return $this->getSharedConfig()->getTwigFunctionName();
    }
}
