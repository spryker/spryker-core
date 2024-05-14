<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Configuration;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

interface DynamicEntityConfigurationResponseInterface
{
    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function getDynamicEntityConfigurationTransfer(): DynamicEntityConfigurationTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function getErrorTransfers(): array;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return $this
     */
    public function setDynamicConfigurationTransfer(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): self;

    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     *
     * @return $this
     */
    public function addErrorTransfer(ErrorTransfer $errorTransfer);
}
