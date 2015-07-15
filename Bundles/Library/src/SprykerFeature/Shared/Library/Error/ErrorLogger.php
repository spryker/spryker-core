<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerFeature\Shared\Library\Application\Version;
use SprykerFeature\Shared\Lumberjack\Code\Lumberjack;
use SprykerFeature\Shared\Lumberjack\Code\Log\Types;

class ErrorLogger
{

    /**
     * @param \Exception $exception
     */
    public static function log(\Exception $exception)
    {
        self::sendExceptionToFile($exception);
        self::sendExceptionToNewRelic($exception);
        self::sendExceptionToLumberjack($exception);
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     */
    protected static function sendExceptionToLumberjack(\Exception $exception, $ignoreInternalExceptions = false)
    {
        try {
            $lumberjack = Lumberjack::getInstance();
            $lumberjack->addField('message', $exception->getMessage());
            $lumberjack->addField('trace', '<pre>' . $exception->getTraceAsString() . '</pre>');
            $lumberjack->addField('className', get_class($exception));
            $lumberjack->addField('fileName', $exception->getFile());
            $lumberjack->addField('line', $exception->getLine());
            $lumberjack->send(
                Types::EXCEPTION,
                $exception->getMessage(),
                Types::EXCEPTION
            );
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToNewRelic($internalException, true);
            }
        }
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     */
    protected static function sendExceptionToNewRelic(\Exception $exception, $ignoreInternalExceptions = false)
    {
        try {
            self::addDeploymentInfo();
            self::addLumberjackRequestId();
            $message = $message = get_class($exception) . ' - ' . $exception->getMessage() . ' in file "' . $exception->getFile() . '"';
            \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->noticeError($message, $exception);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToLumberjack($internalException, true);
            }
        }
    }

    protected static function addLumberjackRequestId()
    {
        $requestId = Lumberjack::getInstance()->getRequestId();
        \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomParameter('LumberjackId', $requestId);
    }

    protected static function addDeploymentInfo()
    {
        $deployData = (new Version())->toArray();
        foreach ($deployData as $name => $data) {
            if (!empty($data)) {
                \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->addCustomParameter('Deployment_' . $name, $data);
            }
        }
    }

    /**
     * @param \Exception $exception
     */
    protected static function sendExceptionToFile(\Exception $exception)
    {
        try {
            $message = ErrorRenderer::renderException($exception);
            \SprykerFeature_Shared_Library_Log::log($message, 'exception.log');
        } catch (\Exception $internalException) {
            self::sendExceptionToLumberjack($internalException, true);
            self::sendExceptionToNewRelic($internalException, true);
        }
    }

}
