<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Installer\PathFinder;

use Symfony\Component\Finder\Finder;

class InstallPathFinder implements PathFinderInterface
{
    /**
     * @var array
     */
    protected $pathPattern;

    /**
     * @param array $pathPattern
     */
    public function __construct(array $pathPattern)
    {
        $this->pathPattern = $pathPattern;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function find()
    {
        $finder = new Finder();

        $finder->files()->in($this->pathPattern)->name('package.json')->depth('< 2');

        return $finder;
    }
}
