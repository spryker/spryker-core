<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ModuleTransfer;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ComposerJsonFinder implements ComposerJsonFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $module
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    public function findByModule(ModuleTransfer $module): ?SplFileInfo
    {
        $this->finder->in($module->getPath())->name('composer.json')->depth('< 1');

        if (!$this->finder->hasResults()) {
            return null;
        }

        return iterator_to_array($this->finder, false)[0];
    }
}
