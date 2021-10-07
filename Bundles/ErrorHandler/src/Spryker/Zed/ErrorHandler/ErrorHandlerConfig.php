<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Shared\ErrorHandler\ErrorHandlerConfig getSharedConfig()
 */
class ErrorHandlerConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isPrettyErrorHandlerEnabled(): bool
    {
        return $this->getSharedConfig()->isPrettyErrorHandlerEnabled();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUserBasePath(): string
    {
        return $this->getSharedConfig()->getUserBasePath();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getServerBasePath(): string
    {
        return $this->getSharedConfig()->getServerBasePath();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIdeLink(): string
    {
        return $this->getSharedConfig()->getIdeLink();
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isAjaxRequiredByIde(): bool
    {
        return $this->getSharedConfig()->isAjaxRequiredByIde();
    }

    /**
     * @api
     *
     * @return array<int>
     */
    public function getValidSubRequestExceptionStatusCodes(): array
    {
        return [
            Response::HTTP_NOT_FOUND,
        ];
    }
}
