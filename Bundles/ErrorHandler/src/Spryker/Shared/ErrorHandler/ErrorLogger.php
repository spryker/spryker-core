<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use Exception;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\NewRelic\NewRelicApiTrait;

class ErrorLogger implements ErrorLoggerInterface
{

    use LoggerTrait;
    use NewRelicApiTrait;

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return void
     */
    public function log($exception)
    {
        try {
            $message = $this->buildMessage($exception);
            $this->createNewRelicApi()->noticeError($message, $exception);
            $this->getLogger()->critical($message, ['exception' => $exception]);
        } catch (Throwable $internalException) {
            $this->createNewRelicApi()->noticeError($internalException->getMessage(), $exception);
        } catch (Exception $internalException) {
            $this->createNewRelicApi()->noticeError($internalException->getMessage(), $exception);
        }
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    protected function buildMessage($exception)
    {
        return sprintf(
            '%s - %s in "%s::%d"',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }

}
