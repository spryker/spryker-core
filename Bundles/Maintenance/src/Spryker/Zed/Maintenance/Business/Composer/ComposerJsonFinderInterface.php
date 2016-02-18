<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface ComposerJsonFinderInterface
{

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function find();

}
