<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsContentWidgetConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
