<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface LogConstants
{
    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CONFIG_YVES = 'LOG:LOGGER_CONFIG_YVES';

    /**
     * Specification:
     * - Channel name of Yves logger.
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CHANNEL_YVES = 'LOG:LOGGER_CHANNEL_YVES';

    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CONFIG_ZED = 'LOG:LOGGER_CONFIG_ZED';

    /**
     * Specification:
     * - Channel name of Zed logger.
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CHANNEL_ZED = 'LOG:LOGGER_CHANNEL_ZED';

    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CONFIG = 'LOGGER_CONFIG';

    /**
     * Specification:
     * - Log level were to start to log. E.g. when set to error, info messages will not be logged
     *
     * @api
     *
     * @var string
     */
    public const LOG_LEVEL = 'LOG_LEVEL';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @deprecated Use the application specific constants. E.g. LOG_FILE_PATH_YVES
     *
     * @api
     *
     * @var string
     */
    public const LOG_FILE_PATH = 'LOG_FILE_PATH';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @api
     *
     * @var string
     */
    public const LOG_FILE_PATH_YVES = 'LOG_FILE_PATH_YVES';

    /**
     * Specification:
     * - Absolute path to the installation log files directory.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     *
     * @var string
     */
    public const LOG_FOLDER_PATH_INSTALLATION = 'LOG:LOG_FOLDER_PATH_INSTALLATION';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @api
     *
     * @var string
     */
    public const LOG_FILE_PATH_ZED = 'LOG_FILE_PATH_ZED';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @deprecated Use the application specific constants. E.g. EXCEPTION_LOG_FILE_PATH_YVES
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_LOG_FILE_PATH = 'LOG:EXCEPTION_LOG_FILE_PATH';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_LOG_FILE_PATH_YVES = 'LOG:EXCEPTION_LOG_FILE_PATH_YVES';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_LOG_FILE_PATH_ZED = 'LOG:EXCEPTION_LOG_FILE_PATH_ZED';

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
     *
     * @var string
     */
    public const LOG_SANITIZE_FIELDS = 'LOG_SANITIZE_FIELDS';

    /**
     * Specification:
     * - Provides an array with names which is used to sanitize data in your audit logs.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOG_SANITIZE_FIELDS = 'LOG:AUDIT_LOG_SANITIZE_FIELDS';

    /**
     * Specification:
     * - String which is used as value for the sanitized field
     *
     * @api
     *
     * @var string
     */
    public const LOG_SANITIZED_VALUE = 'LOG_SANITIZED_VALUE';

    /**
     * Specification:
     * - Provides a string which is used as value for the audit log sanitized fields.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOG_SANITIZED_VALUE = 'LOG:AUDIT_LOG_SANITIZED_VALUE';

    /**
     * Specification:
     * - Provides a list of audit log tags that are disallowed for logging.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOG_TAG_DISALLOW_LIST = 'LOG:AUDIT_LOG_TAG_DISALLOW_LIST';

    /**
     * Specification:
     * - Name of the queue to send logs to.
     *
     * @api
     *
     * @var string
     */
    public const LOG_QUEUE_NAME = 'LOG:LOG_QUEUE_NAME';

    /**
     * Specification:
     * - Name of the error queue to send errors to.
     *
     * @api
     *
     * @var string
     */
    public const LOG_ERROR_QUEUE_NAME = 'LOG:LOG_ERROR_QUEUE_NAME';

    /**
     * Specification:
     * - Class name of the class which implements LoggerConfigInterface. E.g. SprykerLoggerConfig::class
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CONFIG_GLUE = 'LOG:LOGGER_CONFIG_GLUE';

    /**
     * Specification:
     * - Channel name of Glue logger.
     *
     * @api
     *
     * @var string
     */
    public const LOGGER_CHANNEL_GLUE = 'LOG:LOGGER_CHANNEL_GLUE';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler. E.g. /var/www/data/logs/spryker.log
     * - If set logs will be written to the specified file.
     *
     * @api
     *
     * @var string
     */
    public const LOG_FILE_PATH_GLUE = 'LOG_FILE_PATH_GLUE';

    /**
     * Specification:
     * - Absolute path to the log file which should be used be the stream handler for exceptions. E.g. /var/www/data/logs/spryker.log
     *
     * @api
     *
     * @var string
     */
    public const EXCEPTION_LOG_FILE_PATH_GLUE = 'LOG:EXCEPTION_LOG_FILE_PATH_GLUE';

    /**
     * Specification:
     * - Provides class names of the plugins implementing {@link \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface}
     * which are used to provide configuration for audit logging for Yves application.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CONFIG_PLUGINS_YVES = 'LOG:AUDIT_LOGGER_CONFIG_PLUGINS_YVES';

    /**
     * Specification:
     * - Provides class names of the plugins implementing {@link \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface}
     * which are used to provide configuration for audit logging for Zed application.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CONFIG_PLUGINS_ZED = 'LOG:AUDIT_LOGGER_CONFIG_PLUGINS_ZED';

    /**
     * Specification:
     * - Provides class names of the plugins implementing {@link \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface}
     * which are used to provide configuration for audit logging for Glue application.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CONFIG_PLUGINS_GLUE = 'LOG:AUDIT_LOGGER_CONFIG_PLUGINS_GLUE';

    /**
     * Specification:
     * - Provides class names of the plugins implementing {@link \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface}
     * which are used to provide configuration for audit logging for Glue Backend application.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CONFIG_PLUGINS_GLUE_BACKEND = 'LOG:AUDIT_LOGGER_CONFIG_PLUGINS_GLUE_BACKEND';

    /**
     * Specification:
     * - Provides class names of the plugins implementing {@link \Spryker\Shared\LogExtension\Dependency\Plugin\AuditLoggerConfigPluginInterface}
     * which are used to provide configuration for audit logging for Merchant Portal application.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CONFIG_PLUGINS_MERCHANT_PORTAL = 'LOG:AUDIT_LOGGER_CONFIG_PLUGINS_MERCHANT_PORTAL';
}
