<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngineExtension\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Allows to modify data that is passed to step template.
 */
interface StepEnginePreRenderPluginInterface
{
    /**
     * Specifications:
     * - Prepares data for each step of process, before template rendering.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(AbstractTransfer $dataTransfer): AbstractTransfer;
}
