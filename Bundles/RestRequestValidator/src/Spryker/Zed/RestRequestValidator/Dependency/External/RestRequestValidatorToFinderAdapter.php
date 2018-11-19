<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\External;

use Symfony\Component\Finder\Finder;

class RestRequestValidatorToFinderAdapter implements RestRequestValidatorToFinderAdapterInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    public function __construct()
    {
        $this->finder = Finder::create();
    }

    /**
     * @param array $dirs
     *
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function in(array $dirs): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder->in($dirs);

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function name(string $pattern): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder->name($pattern);

        return $this;
    }

    /**
     * @return \Iterator|\SplFileInfo[]
     */
    public function getIterator()
    {
        return $this->finder->getIterator();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->finder->count();
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapterInterface
     */
    public function reset(): RestRequestValidatorToFinderAdapterInterface
    {
        $this->finder = Finder::create();

        return $this;
    }
}
