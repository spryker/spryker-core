<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

interface LogConstants
{
    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     */
    const LOGGER_CONFIG_YVES = 'LOG:LOGGER_CONFIG_YVES';

    /**
     * Specification:
     * - Channel name of Yves logger.
     *
     * @api
     */
    const LOGGER_CHANNEL_YVES = 'LOG:LOGGER_CHANNEL_YVES';

    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     */
    const LOGGER_CONFIG_ZED = 'LOG:LOGGER_CONFIG_ZED';

    /**
     * Specification:
     * - Channel name of Zed logger.
     *
     * @api
     */
    const LOGGER_CHANNEL_ZED = 'LOG:LOGGER_CHANNEL_ZED';

    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     */
    const LOGGER_CONFIG = 'LOGGER_CONFIG';

    /**
     * Specification:
     * - Log level were to start to log. E.g. when set to error, info messages will not be logged
     *
     * @api
     */
    const LOG_LEVEL = 'LOG_LEVEL';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @deprecated Please use the application specific constants. E.g. LOG_FILE_PATH_YVES
     *
     * @api
     */
    const LOG_FILE_PATH = 'LOG_FILE_PATH';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @api
     */
    const LOG_FILE_PATH_YVES = 'LOG_FILE_PATH_YVES';

    /**
     * Specification:
     * - Absolute path to the log files directory which should be used by the stream handler. E.g. /var/www/data/install/logs/
     *
     * @api
     */
    const LOG_FOLDER_PATH_INSTALLATION = 'LOG_FOLDER_PATH_INSTALLATION';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @api
     */
    const LOG_FILE_PATH_ZED = 'LOG_FILE_PATH_ZED';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @deprecated Please use the application specific constants. E.g. EXCEPTION_LOG_FILE_PATH_YVES
     *
     * @api
     */
    const EXCEPTION_LOG_FILE_PATH = 'LOG:EXCEPTION_LOG_FILE_PATH';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @api
     */
    const EXCEPTION_LOG_FILE_PATH_YVES = 'LOG:EXCEPTION_LOG_FILE_PATH_YVES';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @api
     */
    const EXCEPTION_LOG_FILE_PATH_ZED = 'LOG:EXCEPTION_LOG_FILE_PATH_ZED';

    /**
     * Specification:
     * - Array with names which is used to sanitize data in your logs.
     *
     * The data which goes to the sanitizer is an array. Before it gets formatted the sanitizer will iterate of the given
     * data set and if the key is matching it will use the LOG_SANITIZED_VALUE as a new value for the given key.
     *
     * Example:
     *
     * $config[LogConstants::LOG_SANITIZE_FIELDS] = [
     *     'password'
     * ];
     *
     * $recordData = [
     *     'username' => 'spryker',
     *     'password' => 'my super secret password'
     * ];
     *
     * After the sanitizer was running you will get:
     *
     * $recordData = [
     *     'username' => 'spryker',
     *     'password' => '***'
     * ];
     *
     * @api
     */
    const LOG_SANITIZE_FIELDS = 'LOG_SANITIZE_FIELDS';

    /**
     * Specification:
     * - String which is used as value for the sanitized field
     *
     * @api
     */
    const LOG_SANITIZED_VALUE = 'LOG_SANITIZED_VALUE';

    /**
     * Specification:
     * - Name of the queue to send logs to.
     *
     * @api
     */
    const LOG_QUEUE_NAME = 'LOG:LOG_QUEUE_NAME';

    /**
     * Specification:
     * - Name of the error queue to send errors to.
     *
     * @api
     */
    const LOG_ERROR_QUEUE_NAME = 'LOG:LOG_ERROR_QUEUE_NAME';
}
