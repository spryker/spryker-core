<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Configuration;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class DynamicEntityConfigurationResponse implements DynamicEntityConfigurationResponseInterface
{
    /**
     * @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    protected DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer;

    /**
     * @var array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected array $errorTransfers = [];

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function getDynamicEntityConfigurationTransfer(): DynamicEntityConfigurationTransfer
    {
        return $this->dynamicEntityConfigurationTransfer;
    }

    /**
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function getErrorTransfers(): array
    {
        return $this->errorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface
     */
    public function setDynamicConfigurationTransfer(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationResponseInterface {
        $this->dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTransfer;

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorTransfer $errorTransfer
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface
     */
    public function addErrorTransfer(
        ErrorTransfer $errorTransfer
    ): DynamicEntityConfigurationResponseInterface {
        $this->errorTransfers[] = $errorTransfer;

        return $this;
    }
}
