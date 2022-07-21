<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component;

/**
 * Specification:
 *  - This component describes a single request body.
 *  - It covers Request Body Object in OpenAPI specification format (see https://swagger.io/specification/#requestBodyObject)
 */
class PathRequestSpecificationComponent implements PathRequestSpecificationComponentInterface
{
    /**
     * @var string
     */
    protected const DESCRIPTION_DEFAULT_REQUEST = 'Expected request body.';

    /**
     * @var string
     */
    protected const KEY_APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const KEY_REF = '$ref';

    /**
     * @var string
     */
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const KEY_REQUIRED = 'required';

    /**
     * @param array<mixed> $pathMethodData
     * @param string $requestSchemaName
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(array $pathMethodData, string $requestSchemaName): array
    {
        $requestComponentData = [];

        $requestComponentData[static::KEY_DESCRIPTION] = static::DESCRIPTION_DEFAULT_REQUEST;
        $requestComponentData[static::KEY_REQUIRED] = true;
        $requestComponentData[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $requestSchemaName;

        return $requestComponentData;
    }
}
