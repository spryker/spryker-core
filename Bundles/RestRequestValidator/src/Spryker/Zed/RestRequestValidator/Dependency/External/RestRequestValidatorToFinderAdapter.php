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
        $this->finder = new Finder();
    }

    /**
     * @param array $dirs
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function in(array $dirs): Finder
    {
        return $this->finder->in($dirs);
    }

    /**
     * @param string $pattern
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function name(string $pattern): Finder
    {
        return $this->finder->name($pattern);
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
}
