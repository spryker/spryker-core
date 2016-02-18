<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ComposerJsonFinder implements ComposerJsonFinderInterface
{

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $pathToBundles;

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
        return $this->finder->in($this->pathToBundles)->name('composer.json');
    }

}
