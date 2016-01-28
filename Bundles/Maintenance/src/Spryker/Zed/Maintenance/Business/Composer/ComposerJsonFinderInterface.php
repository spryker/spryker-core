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
     * @return Finder|SplFileInfo[]
     */
    public function find();
}
