<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Flysystem\OperationHandler;

use Closure;
use Exception;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException;
use Throwable;

trait StreamOperationHandlerTrait
{
    /**
     * @param \Closure $callback
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     * @throws \Exception
     *
     * @return mixed
     */
    protected function handleStreamOperation(Closure $callback)
    {
        try {
            $result = $callback();
            if (is_bool($result) && !$result) {
                throw new Exception('Stream operation failed');
            }

            return $result;
        } catch (Exception $exception) {
            throw new FileSystemStreamException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (Throwable $exception) {
            throw new FileSystemStreamException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
