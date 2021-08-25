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
    protected const REQUEST_PARAM_EXCEPTION = 'exception';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function error404Action(Request $request): array
    {
        return $this->viewResponse([
            'error' => $this->getErrorMessage($request),
            'hideUserMenu' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getErrorMessage(Request $request): string
    {
        /** @var \Symfony\Component\ErrorHandler\Exception\FlattenException|null $exception */
        $exception = $request->query->get(static::REQUEST_PARAM_EXCEPTION);

        if ($exception instanceof FlattenException) {
            return $exception->getMessage();
        }

        return '';
    }
}
