<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ErrorHandlerConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isPrettyErrorHandlerEnabled(): bool
    {
        return $this->get(ErrorHandlerConstants::IS_PRETTY_ERROR_HANDLER_ENABLED, false);
    }

    /**
     * @return string
     */
    public function getUserBasePath(): string
    {
        return $this->get(ErrorHandlerConstants::USER_BASE_PATH, '');
    }

    /**
     * @return string
     */
    public function getServerBasePath(): string
    {
        return $this->get(ErrorHandlerConstants::SERVER_BASE_PATH, '/data/shop/development/current');
    }

    /**
     * @return string
     */
    public function getIdeLink(): string
    {
        return $this->get(ErrorHandlerConstants::PATTERN_IDE_LINK, 'phpstorm://open?file=%s&line=%s');
    }

    /**
     * @return bool
     */
    public function isAjaxRequiredByIde(): bool
    {
        return $this->get(ErrorHandlerConstants::AS_AJAX, false);
    }
}
