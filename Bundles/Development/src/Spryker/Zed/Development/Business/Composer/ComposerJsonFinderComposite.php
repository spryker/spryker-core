<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

class ComposerJsonFinderComposite implements ComposerJsonFinderCompositeInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface[]
     */
    protected $finder = [];

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface $finder
     *
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderCompositeInterface
     */
    public function addFinder(ComposerJsonFinderInterface $finder): ComposerJsonFinderCompositeInterface
    {
        $this->finder[] = $finder;

        return $this;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $composerJsonFiles = [];
        foreach ($this->finder as $finder) {
            $composerJsonFiles = array_merge($composerJsonFiles, $finder->findAll());
        }

        return $composerJsonFiles;
    }
}
