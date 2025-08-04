<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

interface PathMethodBuilderInterface
{
    /**
     * @var string
     */
    public const KEY_PATHS = 'paths';

    /**
     * @var string
     */
    public const KEY_TAGS = 'tags';

    /**
     * @var string
     */
    public const KEY_OPERATION_ID = 'operationId';

    /**
     * @var string
     */
    public const KEY_SUMMARY = 'summary';

    /**
     * @var string
     */
    public const KEY_PARAMETERS = 'parameters';

    /**
     * @var string
     */
    public const KEY_REQUEST_BODY = 'requestBody';

    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_IN = 'in';

    /**
     * @var string
     */
    public const KEY_REQUIRED = 'required';

    /**
     * @var string
     */
    public const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    public const KEY_TYPE = 'type';

    /**
     * @var string
     */
    public const KEY_STYLE = 'style';

    /**
     * @var string
     */
    public const KEY_EXPLODE = 'explode';

    /**
     * @var string
     */
    public const KEY_EXAMPLE = 'example';

    /**
     * @var string
     */
    public const KEY_RESPONSES = 'responses';

    /**
     * @var string
     */
    public const KEY_RESPONSE_DEFAULT = 'default';

    /**
     * @var string
     */
    public const KEY_SCHEMA_ITEMS = 'items';

    /**
     * @var string
     */
    public const KEY_SCHEMA_PROPERTIES = 'properties';

    /**
     * @var string
     */
    public const KEY_SCHEMA_REF = '$ref';

    /**
     * @var string
     */
    public const SCHEMA_REF_COMPONENT_REST_ERROR = '#/components/schemas/RestErrorMessage';

    /**
     * @var string
     */
    public const SCHEMA_TYPE_OBJECT = 'object';

    /**
     * @var string
     */
    public const SCHEMA_TYPE_ARRAY = 'array';

    /**
     * @var string
     */
    public const SCHEMA_TYPE_STRING = 'string';

    /**
     * @var string
     */
    public const SCHEMA_TYPE_INTEGER = 'integer';

    /**
     * @var string
     */
    public const SCHEMA_OFFSET = 'offset';

    /**
     * @var string
     */
    public const SCHEMA_LIMIT = 'limit';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array;
}
