<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Development\Business\DevelopmentBusinessFactory;

class ComposerJsonFinderCompositeBuilder
{
    /**
     * @var \Spryker\Zed\Development\Business\DevelopmentBusinessFactory
     */
    protected $factory;

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderCompositeInterface
     */
    protected $composerJsonFinderComposite;

    /**
     * @param \Spryker\Zed\Development\Business\DevelopmentBusinessFactory $factory
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderCompositeInterface $composerJsonFinderComposite
     */
    public function __construct(DevelopmentBusinessFactory $factory, ComposerJsonFinderCompositeInterface $composerJsonFinderComposite)
    {
        $this->factory = $factory;
        $this->composerJsonFinderComposite = $composerJsonFinderComposite;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer[] $modules
     *
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderCompositeInterface
     */
    public function build(array $modules): ComposerJsonFinderCompositeInterface
    {
        foreach ($modules as $module) {
            $this->composerJsonFinderComposite->addFinder($this->createComposerJsonFinder($module));
        }

        return $this->composerJsonFinderComposite;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $module
     *
     * @return \Spryker\Zed\Development\Business\Composer\ComposerJsonFinderInterface
     */
    protected function createComposerJsonFinder(ModuleTransfer $module): ComposerJsonFinderInterface
    {
        return $this->factory->createComposerJsonFinder($module->getPath());
    }
}
