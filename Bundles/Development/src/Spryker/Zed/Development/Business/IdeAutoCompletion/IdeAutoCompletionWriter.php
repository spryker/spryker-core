<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion;

use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface;

class IdeAutoCompletionWriter implements IdeAutoCompletionWriterInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface[]
     */
    protected $generators;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface
     */
    protected $moduleFinder;

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface[] $generators
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface $moduleFinder
     */
    public function __construct(array $generators, BundleFinderInterface $moduleFinder)
    {
        $this->generators = $generators;
        $this->moduleFinder = $moduleFinder;
    }

    /**
     * @return void
     */
    public function writeCompletionFiles()
    {
        $moduleTransferCollection = $this->moduleFinder->find();

        foreach ($this->generators as $generator) {
            $generator->generate($moduleTransferCollection);
        }
    }
}
