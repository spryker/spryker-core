<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use Spryker\Shared\Application\ApplicationConstants;

interface LibraryConstants
{

    const CLOUD_CDN_ENABLED = ApplicationConstants::CLOUD_CDN_ENABLED;
    const CLOUD_CDN_PRODUCT_IMAGES_PATH_NAME = ApplicationConstants::CLOUD_CDN_PRODUCT_IMAGES_PATH_NAME;
    const CLOUD_CDN_STATIC_MEDIA_HTTP = ApplicationConstants::CLOUD_CDN_STATIC_MEDIA_HTTP;
    const CLOUD_CDN_STATIC_MEDIA_HTTPS = ApplicationConstants::CLOUD_CDN_STATIC_MEDIA_HTTPS;
    const CLOUD_CDN_STATIC_MEDIA_PREFIX = ApplicationConstants::CLOUD_CDN_STATIC_MEDIA_PREFIX;
    const CLOUD_ENABLED = ApplicationConstants::CLOUD_ENABLED;
    const CLOUD_OBJECT_STORAGE_ENABLED = ApplicationConstants::CLOUD_OBJECT_STORAGE_ENABLED;
    const CORE_NAMESPACES = ApplicationConstants::CORE_NAMESPACES;

    const DISPLAY_ERRORS = ApplicationConstants::DISPLAY_ERRORS;

    const ELASTICA_PARAMETER__HOST = ApplicationConstants::ELASTICA_PARAMETER__HOST;
    const ELASTICA_PARAMETER__PORT = ApplicationConstants::ELASTICA_PARAMETER__PORT;
    const ELASTICA_PARAMETER__AUTH_HEADER = ApplicationConstants::ELASTICA_PARAMETER__AUTH_HEADER;
    const ELASTICA_PARAMETER__TRANSPORT = ApplicationConstants::ELASTICA_PARAMETER__TRANSPORT;
    const ERROR_LEVEL = ApplicationConstants::ERROR_LEVEL;
    const HOST_SSL_STATIC_MEDIA = ApplicationConstants::HOST_SSL_STATIC_MEDIA;

    const HOST_STATIC_MEDIA = ApplicationConstants::HOST_STATIC_MEDIA;
    const HOST_YVES = ApplicationConstants::HOST_YVES;
    const HOST_ZED_API = ApplicationConstants::HOST_ZED_API;
    const HOST_ZED_GUI = ApplicationConstants::HOST_ZED_GUI;

    const PRODUCT_IMAGE_IMAGE_URL_PREFIX = 'PRODUCT_IMAGE_IMAGE_URL_PREFIX';

    const STORAGE_KV_SOURCE = ApplicationConstants::STORAGE_KV_SOURCE;

    const TRANSFER_DEBUG_SESSION_FORWARD_ENABLED = ApplicationConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED;
    const TRANSFER_DEBUG_SESSION_NAME = ApplicationConstants::TRANSFER_DEBUG_SESSION_NAME;

    /**
     * @deprecated Use ErrorConstants::YVES_ERROR_PAGE instead.
     */
    const YVES_ERROR_PAGE = ApplicationConstants::YVES_ERROR_PAGE;

    /**
     * @deprecated Use ErrorConstants::ERROR_RENDERER to choose renderer.
     */
    const YVES_SHOW_EXCEPTION_STACK_TRACE = ApplicationConstants::YVES_SHOW_EXCEPTION_STACK_TRACE;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_HOST = ApplicationConstants::YVES_STORAGE_SESSION_REDIS_HOST;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_PERSISTENT_CONNECTION = ApplicationConstants::YVES_STORAGE_SESSION_PERSISTENT_CONNECTION;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PORT = ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PORT;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PROTOCOL = ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL;

    /**
     * @deprecated Use Session bundle SessionConstants class constant.
     */
    const YVES_STORAGE_SESSION_REDIS_PASSWORD = ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PASSWORD;

    /**
     * @deprecated Use ErrorConstants::ZED_ERROR_PAGE instead.
     */
    const ZED_ERROR_PAGE = ApplicationConstants::ZED_ERROR_PAGE;

    /**
     * @deprecated Use ErrorConstants::ERROR_RENDERER to choose renderer.
     */
    const ZED_SHOW_EXCEPTION_STACK_TRACE = ApplicationConstants::ZED_SHOW_EXCEPTION_STACK_TRACE;

}
