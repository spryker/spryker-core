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
    protected const COMPOSER_JSON_FILE_NAME = 'composer.json';

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
        $currentFinderInstance = $this->getNewFinderInstance();
        $currentFinderInstance
            ->in($module->getPath())
            ->name(static::COMPOSER_JSON_FILE_NAME)
            ->depth('< 1');

        if (!$currentFinderInstance->hasResults()) {
            return null;
        }

        return iterator_to_array($currentFinderInstance, false)[0];
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getNewFinderInstance(): Finder
    {
        return $this->finder::create();
    }
}
