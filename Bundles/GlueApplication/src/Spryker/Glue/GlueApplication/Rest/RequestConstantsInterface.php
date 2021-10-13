<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

interface RequestConstantsInterface
{
    /**
     * @var string
     */
    public const ATTRIBUTE_TYPE = 'type';
    /**
     * @var string
     */
    public const ATTRIBUTE_ID = 'id';
    /**
     * @var string
     */
    public const ATTRIBUTE_MODULE = 'module';
    /**
     * @var string
     */
    public const ATTRIBUTE_CONTROLLER = 'controller';
    /**
     * @var string
     */
    public const ATTRIBUTE_CONFIGURATION = 'route';
    /**
     * @var string
     */
    public const ATTRIBUTE_RESOURCE_FQCN = 'resource-classname';
    /**
     * @var string
     */
    public const ATTRIBUTE_PARENT_RESOURCE = 'parent';
    /**
     * @var string
     */
    public const ATTRIBUTE_ALL_RESOURCES = 'all-resources';
    /**
     * @var string
     */
    public const ATTRIBUTE_CONTEXT = 'route-context';
    /**
     * @var string
     */
    public const ATTRIBUTE_IS_PROTECTED = 'is-protected';
    /**
     * @var string
     */
    public const ATTRIBUTE_RESOURCE_VERSION = 'resource-version';

    /**
     * @var string
     */
    public const HEADER_ACCEPT = 'accept';
    /**
     * @var string
     */
    public const HEADER_ACCEPT_LANGUAGE = 'accept-language';
    /**
     * @var string
     */
    public const HEADER_CONTENT_TYPE = 'content-type';
    /**
     * @var string
     */
    public const HEADER_CONTENT_LANGUAGE = 'content-language';
    /**
     * @var string
     */
    public const HEADER_AUTHORIZATION = 'authorization';
    /**
     * @var string
     */
    public const HEADER_ORIGIN = 'origin';
    /**
     * @var string
     */
    public const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'access-control-allow-methods';
    /**
     * @var string
     */
    public const HEADER_ACCESS_CONTROL_ALLOW_ORIGIN = 'access-control-allow-origin';
    /**
     * @var string
     */
    public const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';
    /**
     * @var string
     */
    public const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';
    /**
     * @var string
     */
    public const HEADER_ACCESS_CONTROL_REQUEST_HEADERS = 'access-control-request-headers';
    /**
     * @var string
     */
    public const HEADER_E_TAG = 'ETag';
    /**
     * @var string
     */
    public const HEADER_IF_MATCH = 'If-Match';

    /**
     * @var string
     */
    public const QUERY_INCLUDE = 'include';
    /**
     * @var string
     */
    public const QUERY_FIELDS = 'fields';
    /**
     * @var string
     */
    public const QUERY_PAGE = 'page';
    /**
     * @var string
     */
    public const QUERY_OFFSET = 'offset';
    /**
     * @var string
     */
    public const QUERY_LIMIT = 'limit';
    /**
     * @var string
     */
    public const QUERY_FILTER = 'filter';
    /**
     * @var string
     */
    public const QUERY_SORT = 'sort';
}
