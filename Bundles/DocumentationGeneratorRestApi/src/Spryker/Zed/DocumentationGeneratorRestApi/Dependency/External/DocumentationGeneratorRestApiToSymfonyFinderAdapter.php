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
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function in($dirs): DocumentationGeneratorRestApiToFinderInterface
    {
        $this->finder->in($dirs);

        return $this;
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function files(): DocumentationGeneratorRestApiToFinderInterface
    {
        $this->finder->files();

        return $this;
    }

    /**
     * @param string $pattern
     *
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function name(string $pattern): DocumentationGeneratorRestApiToFinderInterface
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
