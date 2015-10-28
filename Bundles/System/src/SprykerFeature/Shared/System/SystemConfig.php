<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\System;

use SprykerFeature\Shared\Library\ConfigInterface;

interface SystemConfig extends ConfigInterface
{

    const PROJECT_NAMESPACES = 'project namespaces';
    const CORE_NAMESPACES = 'core namespaces';

    const REDIS_PARAMETER__SCHEME = 'scheme';
    const REDIS_PARAMETER__HOST = 'host';
    const REDIS_PARAMETER__PORT = 'port';
    const REDIS_PARAMETER__PATH = 'path';
    const REDIS_PARAMETER__DATABASE = 'database';
    const REDIS_PARAMETER__PASSWORD = 'password';

    const YVES_STORAGE_SESSION_TIME_TO_LIVE = 'YVES_STORAGE_SESSION_TIME_TO_LIVE';
    const YVES_STORAGE_SESSION_REDIS_PROTOCOL = 'YVES_STORAGE_SESSION_REDIS_PROTOCOL';
    const YVES_STORAGE_SESSION_REDIS_HOST = 'YVES_STORAGE_SESSION_REDIS_HOST';
    const YVES_STORAGE_SESSION_REDIS_PORT = 'YVES_STORAGE_SESSION_REDIS_PORT';
    const YVES_STORAGE_SESSION_FILE_PATH = 'YVES_STORAGE_SESSION_FILE_PATH';

    const ZED_STORAGE_SESSION_TIME_TO_LIVE = 'ZED_STORAGE_SESSION_TIME_TO_LIVE';
    const ZED_STORAGE_SESSION_COOKIE_NAME = 'ZED_STORAGE_SESSION_COOKIE_NAME';
    const ZED_STORAGE_SESSION_REDIS_PROTOCOL = 'ZED_STORAGE_SESSION_REDIS_PROTOCOL';
    const ZED_STORAGE_SESSION_REDIS_HOST = 'ZED_STORAGE_SESSION_REDIS_HOST';
    const ZED_STORAGE_SESSION_REDIS_PORT = 'ZED_STORAGE_SESSION_REDIS_PORT';
    const ZED_STORAGE_SESSION_FILE_PATH = 'ZED_STORAGE_SESSION_FILE_PATH';

    const ELASTICA_PARAMETER__HOST = 'host';
    const ELASTICA_PARAMETER__PORT = 'port';
    const ELASTICA_PARAMETER__TRANSPORT = 'port';
    const ELASTICA_PARAMETER__INDEX_NAME = 'index_name';
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'document_type';

    const JENKINS_BASE_URL = 'jenkins_url';
    const JENKINS_DIRECTORY = 'jenkins_dir';

    /**
     * Sessions
     */
    const ZED_SESSION_SAVE_HANDLER = 'ZED_SESSION_SAVE_HANDLER';

    /**
     * SSL
     */
    const ZED_SSL_ENABLED = 'ZED_SSL_ENABLED';
    const ZED_SSL_EXCLUDED = 'ZED_SSL_EXCLUDED';
    const ZED_API_SSL_ENABLED = 'ZED_API_SSL_ENABLED';

    /**
     * Database - DB
     */

    /**
     * @deprecated
     */
    const ZED_MYSQL_USERNAME = 'ZED_MYSQL_USERNAME';

    /**
     * @deprecated
     */
    const ZED_MYSQL_PASSWORD = 'ZED_MYSQL_PASSWORD';

    /**
     * @deprecated
     */
    const ZED_MYSQL_DATABASE = 'ZED_MYSQL_DATABASE';

    /**
     * @deprecated
     */
    const ZED_MYSQL_HOST = 'ZED_MYSQL_HOST';

    /**
     * @deprecated
     */
    const ZED_MYSQL_PORT = 'ZED_MYSQL_PORT';

    /**
     * Database
     */
    const ZED_DB_ENGINE = 'ZED_DB_ENGINE';
    const ZED_DB_USERNAME = 'ZED_DB_USERNAME';
    const ZED_DB_PASSWORD = 'ZED_DB_PASSWORD';
    const ZED_DB_DATABASE = 'ZED_DB_DATABASE';
    const ZED_DB_HOST = 'ZED_DB_HOST';
    const ZED_DB_PORT = 'ZED_DB_PORT';

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
    const CURRENT_APPLICATION_ENV = 'CURRENT_APPLICATION_ENV';
    const CURRENT_APPLICATION_STORE = 'CURRENT_APPLICATION_STORE';

    /**
     * options for password hashing
     * algorithm needs to be changed if there is a more secure one
     * options can be adjusted if needed
     * see https://github.com/ircmaxell/password_compat for options
     * After changing algorithm or options nothing needs to be done, passwords will rehash on demand
     */
    const ZED_LIBRARY_PASSWORD_ALGORITHM = 'zed_library_password_algorithm';
    const ZED_LIBRARY_PASSWORD_OPTIONS = 'zed_library_password_options';

    /**
     * Cloud
     */
    const CLOUD_ENABLED = 'CLOUD_ENABLED';
    const CLOUD_OBJECT_STORAGE_ENABLED = 'CLOUD_OBJECT_STORAGE_ENABLED';
    const CLOUD_OBJECT_STORAGE_PROVIDER_NAME = 'CLOUD_OBJECT_STORAGE_PROVIDER_NAME';
    const CLOUD_OBJECT_STORAGE_DATA_CONTAINERS = 'CLOUD_OBJECT_STORAGE_DATA_CONTAINERS';
    const CLOUD_OBJECT_STORAGE_RACKSPACE = 'CLOUD_OBJECT_STORAGE_RACKSPACE';
    const CLOUD_OBJECT_STORAGE_PRODUCT_IMAGES = 'CLOUD_OBJECT_STORAGE_PRODUCT_IMAGES';

    const CLOUD_CDN_ENABLED = 'CLOUD_CDN_ENABLED';
    const CLOUD_CDN_STATIC_MEDIA_PREFIX = 'CLOUD_CDN_STATIC_MEDIA_PREFIX';
    const CLOUD_CDN_STATIC_MEDIA_HTTP = 'CLOUD_CDN_STATIC_MEDIA_HTTP';
    const CLOUD_CDN_STATIC_MEDIA_HTTPS = 'CLOUD_CDN_STATIC_MEDIA_HTTPS';

    const CLOUD_CDN_STATIC_ASSETS_PREFIX = 'CLOUD_CDN_STATIC_ASSETS_PREFIX';
    const CLOUD_CDN_STATIC_ASSETS_HTTP = 'CLOUD_CDN_STATIC_ASSETS_HTTP';
    const CLOUD_CDN_STATIC_ASSETS_HTTPS = 'CLOUD_CDN_STATIC_ASSETS_HTTPS';

    const CLOUD_CDN_PRODUCT_IMAGES_PATH_NAME = 'CLOUD_CDN_PRODUCT_IMAGES';
    const CLOUD_CDN_DELETE_LOCAL_PROCESSED_IMAGES = 'CLOUD_CDN_DELETE_LOCAL_PROCESSED_IMAGES';
    const CLOUD_CDN_DELETE_LOCAL_ORIGINAL_IMAGES = 'CLOUD_CDN_DELETE_LOCAL_ORIGINAL_IMAGES';

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

    const LOG_LEVEL = 'LOG_LEVEL';

    const PROPEL = 'PROPEL';
    const PROPEL_DEBUG = 'PROPEL_DEBUG';
    const PROPEL_LOGGER = 'PROPEL_LOGGER';

    const CODE_GENERATORS = 'CODE_GENERATORS';

    const STORAGE_KV_SOURCE = 'STORAGE_KV_SOURCE';
    const STORAGE_KV_COUCHBASE = 'STORAGE_KV_COUCHBASE';
    const STORAGE_KV_MEMCACHED = 'STORAGE_KV_MEMCACHED';
    const STORAGE_KV_MYSQL = 'STORAGE_KV_MYSQL';
    const STORAGE_SEARCH_ELASTICSEARCH = 'STORAGE_SEARCH_ELASTICSEARCH';

    const ZED_USER_SETTINGS = 'ZED_USER_SETTINGS';
    const ZED_AUTH_SETTINGS = 'ZED_AUTH_SETTINGS';
    const ZED_ACL_SETTINGS = 'ZED_ACL_SETTINGS';

    const ZED_ERROR_PAGE = 'ZED_ERROR_PAGE';
    const ZED_SHOW_EXCEPTION_STACK_TRACE = 'ZED_SHOW_EXCEPTION_STACK_TRACE';

}
