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
    protected $pathToBundles;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param string $pathToBundles
     */
    public function __construct(Finder $finder, $pathToBundles)
    {
        $this->finder = $finder;
        $this->pathToBundles = $pathToBundles;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find()
    {
        return $this->finder->in($this->pathToBundles)->name('composer.json')->depth('< 2');
    }

}
