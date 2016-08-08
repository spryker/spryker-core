<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application;

use Spryker\Shared\Session\SessionConstants;

interface ApplicationConstants
{

    const ALLOW_INTEGRATION_CHECKS = 'ALLOW_INTEGRATION_CHECKS';
    const COUCHBASE_BUCKET_PREFIX = 'COUCHBASE_BUCKET_PREFIX';
    const DISPLAY_ERRORS = 'DISPLAY_ERRORS';

    const ENABLE_APPLICATION_DEBUG = 'ENABLE_APPLICATION_DEBUG';
    const ENABLE_WEB_PROFILER = 'ENABLE_WEB_PROFILER';
    const NAVIGATION_CACHE_ENABLED = 'navigation cache enabled';
    const NAVIGATION_ENABLED = 'NAVIGATION_ENABLED';
    const SET_REPEAT_DATA = 'SET_REPEAT_DATA';
    const SHOW_SYMFONY_TOOLBAR = 'SHOW_SYMFONY_TOOLBAR'; //deprecated
    const STORE_PREFIX = 'STORE_PREFIX';
    const BACKTRACE_USER_PATH = 'BACKTRACE_USER_PATH';

    // see http://twig.sensiolabs.org/doc/api.html#environment-options
    const YVES_TWIG_OPTIONS = 'YVES_TWIG_OPTIONS';
    const ZED_TWIG_OPTIONS = 'ZED_TWIG_OPTIONS';

    const YVES_THEME = 'YVES_THEME';
    const YVES_TRUSTED_PROXIES = 'YVES_TRUSTED_PROXIES';
    const YVES_TRUSTED_HOSTS = 'YVES_TRUSTED_HOSTS';
    const YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED = 'YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED';
    const YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG = 'YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG';
    const YVES_SSL_ENABLED = 'YVES_SSL_ENABLED';
    const YVES_COMPLETE_SSL_ENABLED = 'YVES_COMPLETE_SSL_ENABLED';
    const YVES_SSL_EXCLUDED = 'YVES_SSL_EXCLUDED';
    /**
     * @deprecated Use Session bundle SessionConstants::YVES_SESSION_SAVE_HANDLER instead.
     */
    const YVES_SESSION_SAVE_HANDLER = 'YVES_SESSION_SAVE_HANDLER';
    /**
     * @deprecated Use Session bundle SessionConstants::YVES_SESSION_COOKIE_NAME instead.
     */
    const YVES_SESSION_NAME = 'YVES_SESSION_NAME';
    /**
     * @deprecated Use Session bundle SessionConstants::YVES_SESSION_COOKIE_DOMAIN instead.
     */
    const YVES_SESSION_COOKIE_DOMAIN = 'YVES_SESSION_COOKIE_DOMAIN';
    /**
     * @deprecated Use Session bundle SessionConstants class constants instead.
     */
    const YVES_COOKIE_DOMAIN = 'YVES_COOKIE_DOMAIN';
    /**
     * @deprecated Use Session bundle SessionConstants class constants instead.
     */
    const YVES_COOKIE_SECURE = 'YVES_COOKIE_SECURE';

    const YVES_COOKIE_VISITOR_ID_NAME = 'YVES_COOKIE_VISITOR_ID_NAME';
    const YVES_COOKIE_VISITOR_ID_VALID_FOR = 'YVES_COOKIE_VISITOR_ID_VALID_FOR';
    const YVES_COOKIE_DEVICE_ID_NAME = 'YVES_COOKIE_DEVICE_ID_NAME';
    const YVES_COOKIE_DEVICE_ID_VALID_FOR = 'YVES_COOKIE_DEVICE_ID_VALID_FOR';

    const YVES_ERROR_PAGE = 'YVES_ERROR_PAGE';
    const YVES_SHOW_EXCEPTION_STACK_TRACE = 'YVES_SHOW_EXCEPTION_STACK_TRACE';

    const TRANSFER_USERNAME = 'TRANSFER_USERNAME';
    const TRANSFER_PASSWORD = 'TRANSFER_PASSWORD';
    const TRANSFER_SSL = 'TRANSFER_SSL';
    const TRANSFER_DEBUG_SESSION_FORWARD_ENABLED = 'TRANSFER_DEBUG_SESSION_FORWARD_ENABLED';
    const TRANSFER_DEBUG_SESSION_NAME = 'TRANSFER_DEBUG_SESSION_NAME';

    const YVES_AUTH_SETTINGS = 'YVES_AUTH_SETTINGS';

    const PROJECT_NAMESPACES = 'PROJECT_NAMESPACES';
    const CORE_NAMESPACES = 'CORE_NAMESPACES';

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PROTOCOL = SessionConstants::YVES_SESSION_REDIS_PROTOCOL;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PASSWORD = SessionConstants::YVES_SESSION_REDIS_PASSWORD;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_HOST = SessionConstants::YVES_SESSION_REDIS_HOST;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PORT = SessionConstants::YVES_SESSION_REDIS_PORT;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_DATABASE = SessionConstants::YVES_SESSION_REDIS_DATABASE;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_FILE_PATH = SessionConstants::YVES_SESSION_FILE_PATH;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_PERSISTENT_CONNECTION = SessionConstants::YVES_SESSION_PERSISTENT_CONNECTION;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_TIME_TO_LIVE = SessionConstants::YVES_SESSION_TIME_TO_LIVE;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_REDIS_PROTOCOL = SessionConstants::ZED_SESSION_REDIS_PROTOCOL;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_REDIS_HOST = SessionConstants::ZED_SESSION_REDIS_HOST;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_REDIS_PORT = SessionConstants::ZED_SESSION_REDIS_PORT;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_REDIS_PASSWORD = SessionConstants::ZED_SESSION_REDIS_PASSWORD;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_REDIS_DATABASE = SessionConstants::ZED_SESSION_REDIS_DATABASE;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_FILE_PATH = SessionConstants::ZED_SESSION_FILE_PATH;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_PERSISTENT_CONNECTION = SessionConstants::ZED_SESSION_PERSISTENT_CONNECTION;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_TIME_TO_LIVE = SessionConstants::ZED_SESSION_TIME_TO_LIVE;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_COOKIE_NAME = SessionConstants::ZED_SESSION_COOKIE_NAME;
    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const ZED_STORAGE_SESSION_COOKIE_SECURE = SessionConstants::ZED_SESSION_COOKIE_SECURE;

    const ELASTICA_PARAMETER__HOST = 'ELASTICA_PARAMETER__HOST';
    const ELASTICA_PARAMETER__PORT = 'ELASTICA_PARAMETER__PORT';
    const ELASTICA_PARAMETER__TRANSPORT = 'ELASTICA_PARAMETER__TRANSPORT';
    const ELASTICA_PARAMETER__INDEX_NAME = 'ELASTICA_PARAMETER__INDEX_NAME';
    const ELASTICA_PARAMETER__AUTH_HEADER = 'ELASTICA_PARAMETER__AUTH_HEADER';
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'ELASTICA_PARAMETER__DOCUMENT_TYPE';

    const JENKINS_BASE_URL = 'JENKINS_BASE_URL';
    const JENKINS_DIRECTORY = 'JENKINS_DIRECTORY';

    /**
     * @deprecated Use Session bundle SessionConstants::ZED_SESSION_SAVE_HANDLER instead.
     */
    const ZED_SESSION_SAVE_HANDLER = 'ZED_SESSION_SAVE_HANDLER';

    /**
     * SSL
     */
    const ZED_SSL_ENABLED = 'ZED_SSL_ENABLED';
    const ZED_SSL_EXCLUDED = 'ZED_SSL_EXCLUDED';
    const ZED_API_SSL_ENABLED = 'ZED_API_SSL_ENABLED';
    const ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED = 'ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED';
    const ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG = 'ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG';

    /**
     * Database
     */
    const ZED_DB_USERNAME = 'ZED_DB_USERNAME';
    const ZED_DB_PASSWORD = 'ZED_DB_PASSWORD';
    const ZED_DB_DATABASE = 'ZED_DB_DATABASE';
    const ZED_DB_HOST = 'ZED_DB_HOST';
    const ZED_DB_PORT = 'ZED_DB_PORT';

    /**
     * Database engines
     */
    const ZED_DB_ENGINE = 'ZED_DB_ENGINE';
    const ZED_DB_ENGINE_MYSQL = 'ZED_DB_ENGINE_MYSQL';
    const ZED_DB_ENGINE_PGSQL = 'ZED_DB_ENGINE_PGSQL';
    const ZED_DB_SUPPORTED_ENGINES = 'ZED_DB_SUPPORTED_ENGINES';

    /**
     * RabbitMQ
     */
    const ZED_RABBITMQ_USERNAME = 'ZED_RABBITMQ_USERNAME';
    const ZED_RABBITMQ_PASSWORD = 'ZED_RABBITMQ_PASSWORD';
    const ZED_RABBITMQ_HOST = 'ZED_RABBITMQ_HOST';
    const ZED_RABBITMQ_PORT = 'ZED_RABBITMQ_PORT';
    const ZED_RABBITMQ_VHOST = 'ZED_RABBITMQ_VHOST';

    /**
     * Global timezone used to for underlying data, timezones for presentation layer can be changed in stores configuration
     */
    const PROJECT_TIMEZONE = 'PROJECT_TIMEZONE';
    const PROJECT_NAMESPACE = 'PROJECT_NAMESPACE';

    /**
     * Cloud
     */
    const CLOUD_ENABLED = 'CLOUD_ENABLED';
    const CLOUD_OBJECT_STORAGE_ENABLED = 'CLOUD_OBJECT_STORAGE_ENABLED';

    const CLOUD_CDN_ENABLED = 'CLOUD_CDN_ENABLED';
    const CLOUD_CDN_STATIC_MEDIA_PREFIX = 'CLOUD_CDN_STATIC_MEDIA_PREFIX';
    const CLOUD_CDN_STATIC_MEDIA_HTTP = 'CLOUD_CDN_STATIC_MEDIA_HTTP';
    const CLOUD_CDN_STATIC_MEDIA_HTTPS = 'CLOUD_CDN_STATIC_MEDIA_HTTPS';
    const CLOUD_CDN_PRODUCT_IMAGES_PATH_NAME = 'CLOUD_CDN_PRODUCT_IMAGES';

    const HOST_YVES = 'HOST_YVES';
    const HOST_ZED_GUI = 'HOST_ZED_GUI';
    const HOST_ZED_API = 'HOST_ZED_API';
    const HOST_STATIC_ASSETS = 'HOST_STATIC_ASSETS';
    const HOST_STATIC_MEDIA = 'HOST_STATIC_MEDIA';

    const HOST_SSL_YVES = 'HOST_SSL_YVES';
    const HOST_SSL_ZED_GUI = 'HOST_SSL_ZED_GUI';
    const HOST_SSL_ZED_API = 'HOST_SSL_ZED_API';
    const HOST_SSL_STATIC_ASSETS = 'HOST_SSL_STATIC_ASSETS';
    const HOST_SSL_STATIC_MEDIA = 'HOST_SSL_STATIC_MEDIA';

    /**
     * @deprecated Use LogConstants::LOG_LEVEL instead
     */
    const LOG_LEVEL = 'LOG_LEVEL';

    const ERROR_LEVEL = 'ERROR_LEVEL';

    const PROPEL = 'PROPEL';
    const PROPEL_DEBUG = 'PROPEL_DEBUG';
    const PROPEL_SHOW_EXTENDED_EXCEPTION = 'PROPEL_SHOW_EXTENDED_EXCEPTION';

    /**
     * @deprecated Use StorageConstants::STORAGE_KV_SOURCE instead
     */
    const STORAGE_KV_SOURCE = 'STORAGE_KV_SOURCE';
    const ZED_ERROR_PAGE = 'ZED_ERROR_PAGE';
    const ZED_SHOW_EXCEPTION_STACK_TRACE = 'ZED_SHOW_EXCEPTION_STACK_TRACE';

    const APPLICATION_SPRYKER_ROOT = 'APPLICATION_SPRYKER_ROOT';

    const APPLICATION_FORM_FACTORY = 'APPLICATION_FORM_FACTORY';

}
