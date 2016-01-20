<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyReport;

use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractDependencyReport
{

    /**
     * @var array
     */
    protected $dependencyReport = [];

    /**
     * @param SplFileInfo $fileInfo
     * @param string $to
     * @param array $meta
     *
     * @return mixed
     */
    abstract public function addDependency(SplFileInfo $fileInfo, $to, array $meta = []);

    /**
     * @return array
     */
    public function getTree()
    {
        return $this->dependencyReport;
    }

}
