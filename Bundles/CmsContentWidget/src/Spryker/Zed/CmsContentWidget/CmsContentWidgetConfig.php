<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsContentWidgetConfig extends AbstractBundleConfig
{
    /**
     * This is cms content widget configuration provider list, its used to get configuration when building widgets.
     * Also to display usage information in cms placeholder edit page
     * Its created in shared because its needed by Yves and Zed.
     *
     * Should be registered in key value pairs where key is function name and value concrete configuration provider.
     *
     * @return \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface[]
     */
    public function getCmsContentWidgetConfigurationProviders()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isEditorButtonEnabled(): bool
    {
        return false;
    }
}
