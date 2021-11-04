<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler\Communication\Plugin\ExceptionHandler;

use Spryker\Zed\ErrorHandlerExtension\Dependency\Plugin\ExceptionHandlerStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Throwable;

/**
 * @method \Spryker\Zed\ErrorHandler\Communication\ErrorHandlerCommunicationFactory getFactory()
 * @method \Spryker\Zed\ErrorHandler\ErrorHandlerConfig getConfig()
 */
class SubRequestExceptionHandlerStrategyPlugin extends AbstractPlugin implements ExceptionHandlerStrategyPluginInterface
{
    /**
     * @var string
     */
    protected const URL_NAME_PREFIX = '/error-handler/error/error';

    /**
     * {@inheritDoc}
     * - Checks if the exception can be handled using statusCode, which we compare with the list of valid status codes.
     *
     * @api
     *
     * @param \Throwable $exception
     *
     * @return bool
     */
    public function canHandle(Throwable $exception): bool
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        return in_array($statusCode, $this->getConfig()->getValidSubRequestExceptionStatusCodes(), true);
    }

    /**
     * {@inheritDoc}
     * - Creates sub request that triggers dedicated error page.
     *
     * @api
     *
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception): Response
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

        $errorPageUrl = sprintf('%s%s', static::URL_NAME_PREFIX, $exception->getStatusCode());
        $cookies = $request->cookies->all();

        $subRequest = Request::create(
            $errorPageUrl,
            Request::METHOD_GET,
            [
                'exception' => $exception,
            ],
            $cookies,
        );

        if ($request->hasSession()) {
            $subRequest->setSession($request->getSession());
        }

        return $this->getFactory()->getKernel()->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    }
}
