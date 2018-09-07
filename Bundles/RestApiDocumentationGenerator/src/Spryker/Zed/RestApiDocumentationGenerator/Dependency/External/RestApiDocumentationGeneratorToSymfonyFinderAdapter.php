<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use Symfony\Component\Finder\Finder;

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
class RestApiDocumentationGeneratorToSymfonyFinderAdapter implements RestApiDocumentationGeneratorToFinderInterface
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
     * @return $this
     */
    public function sortByName()
    {
        $this->finder->sortByName();

        return $this;
    }

    /**
     * @param string|array $dirs
     *
     * @return $this
     */
    public function in($dirs)
    {
        $this->finder->in($dirs);

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function name($pattern)
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
