<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor;
use Spryker\Client\Kernel\AbstractBundleConfig;

class ContentBannerConfig extends AbstractBundleConfig
{
    /**
     * @return \Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface[]
     */
    public function getExecutorList(): array
    {
        return [
            new BannerTermToBannerTypeExecutor(),
        ];
    }
}
