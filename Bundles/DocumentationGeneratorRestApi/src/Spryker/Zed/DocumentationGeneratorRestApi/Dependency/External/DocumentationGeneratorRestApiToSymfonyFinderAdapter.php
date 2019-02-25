<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External;

use Symfony\Component\Finder\Finder;

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
class DocumentationGeneratorRestApiToSymfonyFinderAdapter implements DocumentationGeneratorRestApiToFinderInterface
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
     * @return void
     */
    public function __clone()
    {
        $this->finder = Finder::create();
    }

    /**
     * @param array|string $dirs
     *
     * @return $this
     */
    public function in($dirs)
    {
        $this->finder->in($dirs);

        return $this;
    }

    /**
     * @return $this
     */
    public function files()
    {
        $this->finder->files();

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function name(string $pattern)
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
}
