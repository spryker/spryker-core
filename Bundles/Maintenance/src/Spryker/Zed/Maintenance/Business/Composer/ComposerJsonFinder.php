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
     * @var Finder
     */
    private $finder;

    /**
     * @var string
     */
    private $pathToBundles;

    /**
     * @param Finder $finder
     * @param string $pathToBundles
     */
    public function __construct(Finder $finder, $pathToBundles)
    {
        $this->finder = $finder;
        $this->pathToBundles = $pathToBundles;
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    public function find()
    {
        return $this->finder->in($this->pathToBundles)->name('composer.json');
    }

}
