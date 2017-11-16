<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Symfony\Component\Finder\Finder;

class ComposerJsonFinder implements ComposerJsonFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var string
     */
    protected $pathToModules;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param string $pathToModules
     */
    public function __construct(Finder $finder, $pathToModules)
    {
        $this->finder = $finder;
        $this->pathToModules = $pathToModules;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find()
    {
        return iterator_to_array($this->finder->in($this->pathToModules)->name('composer.json')->depth('< 2'));
    }
}
