<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Flysystem\OperationHandler;

use Closure;
use Exception;
use Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException;
use Throwable;

trait ReadOperationHandlerTrait
{
    /**
     * @param \Closure $callback
     *
     * @throws \Spryker\Service\FileSystemExtension\Dependency\Exception\FileSystemReadException
     * @throws \Exception
     *
     * @return mixed
     */
    protected function handleReadOperation(Closure $callback)
    {
        try {
            $result = $callback();

            if (is_bool($result) && !$result) {
                throw new Exception('Read operation failed');
            }

            return $result;
        } catch (Throwable $exception) {
            throw new FileSystemReadException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
