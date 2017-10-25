<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\CacheWarmer;

use Spryker\Shared\Twig\Cache\CacheWriterInterface;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface;

class CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var \Spryker\Shared\Twig\Cache\CacheWriterInterface
     */
    protected $cacheWriter;

    /**
     * @var \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface
     */
    protected $templatePathMapBuilder;

    /**
     * @param \Spryker\Shared\Twig\Cache\CacheWriterInterface $cacheWriter
     * @param \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface $templatePathMapBuilder
     */
    public function __construct(CacheWriterInterface $cacheWriter, TemplatePathMapBuilderInterface $templatePathMapBuilder)
    {
        $this->cacheWriter = $cacheWriter;
        $this->templatePathMapBuilder = $templatePathMapBuilder;
    }

    /**
     * @return void
     */
    public function warmUp()
    {
        $this->cacheWriter->write($this->templatePathMapBuilder->build());
    }
}
