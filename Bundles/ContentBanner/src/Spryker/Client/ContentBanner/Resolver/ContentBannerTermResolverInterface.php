<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Resolver;

use Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface;

interface ContentBannerTermResolverInterface
{
    /**
     * @param string $term
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface
     */
    public function resolve(string $term): BannerTypeExecutorInterface;
}
