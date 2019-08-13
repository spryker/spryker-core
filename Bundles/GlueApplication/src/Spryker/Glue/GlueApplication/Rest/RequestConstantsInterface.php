<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

interface RequestConstantsInterface
{
    public const ATTRIBUTE_TYPE = 'type';
    public const ATTRIBUTE_ID = 'id';
    public const ATTRIBUTE_MODULE = 'module';
    public const ATTRIBUTE_CONTROLLER = 'controller';
    public const ATTRIBUTE_CONFIGURATION = 'route';
    public const ATTRIBUTE_RESOURCE_FQCN = 'resource-classname';
    public const ATTRIBUTE_PARENT_RESOURCE = 'parent';
    public const ATTRIBUTE_ALL_RESOURCES = 'all-resources';
    public const ATTRIBUTE_CONTEXT = 'route-context';
    public const ATTRIBUTE_IS_PROTECTED = 'is-protected';
    public const ATTRIBUTE_RESOURCE_VERSION = 'resource-version';

    public const HEADER_ACCEPT = 'accept';
    public const HEADER_ACCEPT_LANGUAGE = 'accept-language';
    public const HEADER_CONTENT_TYPE = 'content-type';
    public const HEADER_CONTENT_LANGUAGE = 'content-language';
    public const HEADER_AUTHORIZATION = 'authorization';
    public const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'access-control-allow-methods';
    public const HEADER_ACCESS_CONTROL_ALLOW_ORIGIN = 'access-control-allow-origin';
    public const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';
    public const HEADER_ACCESS_CONTROL_REQUEST_METHOD = 'access-control-request-method';
    public const HEADER_ACCESS_CONTROL_REQUEST_HEADER = 'access-control-request-header';
    public const HEADER_E_TAG = 'ETag';
    public const HEADER_IF_MATCH = 'If-Match';

    public const QUERY_INCLUDE = 'include';
    public const QUERY_FIELDS = 'fields';
    public const QUERY_PAGE = 'page';
    public const QUERY_OFFSET = 'offset';
    public const QUERY_LIMIT = 'limit';
    public const QUERY_FILTER = 'filter';
    public const QUERY_SORT = 'sort';
}
