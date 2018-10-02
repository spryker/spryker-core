<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader;

use Generated\Shared\Transfer\ModuleTransfer;

interface ChoiceLoaderCompositeInterface
{
    /**
     * @param string $choiceLoaderName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(string $choiceLoaderName, ModuleTransfer $moduleTransfer): array;
}
