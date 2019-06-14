<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder;

class FinderComposite implements FinderCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface[]
     */
    protected $finder = [];

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\Finder\FinderInterface $finder
     *
     * @return $this
     */
    public function addFinder(FinderInterface $finder)
    {
        $this->finder[] = $finder;

        return $this;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function find(string $module): array
    {
        $files = [];
        foreach ($this->finder as $finder) {
            $files = array_merge($files, $finder->find($module));
        }

        return $files;
    }
}
