<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ErrorHandler;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ErrorHandler\ErrorHandlerConfig getSharedConfig()
 */
class ErrorHandlerConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isPrettyErrorHandlerEnabled(): bool
    {
        return $this->getSharedConfig()->isPrettyErrorHandlerEnabled();
    }

    /**
     * @return string
     */
    public function getUserBasePath(): string
    {
        return $this->getSharedConfig()->getUserBasePath();
    }

    /**
     * @return string
     */
    public function getServerBasePath(): string
    {
        return $this->getSharedConfig()->getServerBasePath();
    }

    /**
     * @return string
     */
    public function getIdeLink(): string
    {
        return $this->getSharedConfig()->getIdeLink();
    }

    /**
     * @return bool
     */
    public function isAjaxRequiredByIde(): bool
    {
        return $this->getSharedConfig()->isAjaxRequiredByIde();
    }
}
