<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandlerExtension\Dependency\Plugin;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Provides extension capabilities for the exception handling.
 */
interface ExceptionHandlerStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if an exception can be handled by statusCode.
     *
     * @api
     *
     * @param \Throwable $exception
     *
     * @return bool
     */
    public function canHandle(Throwable $exception): bool;

    /**
     * Specification:
     * - Handles an exception.
     *
     * @api
     *
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception): Response;
}
