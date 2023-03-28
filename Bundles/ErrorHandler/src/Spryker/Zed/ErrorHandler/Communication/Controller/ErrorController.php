<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ErrorHandler\Communication\ErrorHandlerCommunicationFactory getFactory()
 */
class ErrorController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_EXCEPTION = 'exception';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function error404Action(Request $request): array
    {
        return $this->viewResponse([
            'error' => $this->defineErrorMessage($request),
            'errorCode' => $this->defineErrorCode($request),
            'hideUserMenu' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function error429Action(Request $request): array
    {
        return $this->viewResponse([
            'error' => $this->defineErrorMessage($request),
            'errorCode' => $this->defineErrorCode($request),
            'hideUserMenu' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string|null
     */
    protected function defineErrorMessage(Request $request): ?string
    {
        /** @var \Symfony\Component\ErrorHandler\Exception\FlattenException|null $exception */
        $exception = $request->query->all()[static::REQUEST_PARAM_EXCEPTION] ?? null;

        if ($exception instanceof FlattenException) {
            return $exception->getMessage();
        }

        return null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int|null
     */
    protected function defineErrorCode(Request $request): ?int
    {
        /** @var \Symfony\Component\ErrorHandler\Exception\FlattenException|null $exception */
        $exception = $request->query->get(static::REQUEST_PARAM_EXCEPTION);

        if ($exception instanceof FlattenException) {
            return (int)$exception->getStatusCode();
        }

        return null;
    }
}
