<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Resolver;

use Spryker\Client\ContentBanner\ContentBannerConfig;
use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
use Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface;

class ContentBannerTermResolver implements ContentBannerTermResolverInterface
{
    /**
     * @var \Spryker\Client\ContentBanner\ContentBannerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\ContentBanner\ContentBannerConfig $config
     */
    public function __construct(ContentBannerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $term
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface
     */
    public function resolve(string $term): BannerTypeExecutorInterface
    {
        foreach ($this->config->getExecutorList() as $executor) {
            if ($executor::getTerm() === $term) {
                return $executor;
            }
        }

        throw new MissingBannerTermException(sprintf('There is no ContentBanner Term which can work with the term %s.', $term));
    }
}
