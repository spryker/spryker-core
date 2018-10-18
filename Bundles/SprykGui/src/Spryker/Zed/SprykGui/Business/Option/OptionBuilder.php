<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Option;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OptionsTransfer;

class OptionBuilder implements OptionBuilderInterface
{
    /**
     * @var \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface[]
     */
    protected $optionBuilder;

    /**
     * @param \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface[] $optionBuilder
     */
    public function __construct(array $optionBuilder)
    {
        $this->optionBuilder = $optionBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function build(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        $moduleTransfer->setOptions(new OptionsTransfer());

        foreach ($this->optionBuilder as $optionBuilder) {
            $moduleTransfer = $optionBuilder->build($moduleTransfer);
        }

        return $moduleTransfer;
    }
}
