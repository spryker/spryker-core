<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\CacheWarmer;

use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;

class CacheWarmerComposite implements CacheWarmerInterface
{
    /**
     * @var \Spryker\Zed\Twig\Business\Model\CacheWarmerInterface[]
     */
    protected $cacheWarmer;

    /**
     * @param \Spryker\Zed\Twig\Business\Model\CacheWarmerInterface[] $cacheWarmer
     */
    public function __construct(array $cacheWarmer)
    {
        $this->cacheWarmer = $cacheWarmer;
    }

    /**
     * @return void
     */
    public function warmUp()
    {
        foreach ($this->cacheWarmer as $cacheWarmer) {
            $cacheWarmer->warmUp();
        }
    }
}
