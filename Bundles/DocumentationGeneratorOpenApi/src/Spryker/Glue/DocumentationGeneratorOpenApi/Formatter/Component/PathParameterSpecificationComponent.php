<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component;

/**
 * Specification:
 *  - This component describes a single operation parameter.
 *  - It covers Parameter Object in OpenAPI specification format (see https://swagger.io/specification/#parameterObject)
 */
class PathParameterSpecificationComponent implements PathParameterSpecificationComponentInterface
{
    /**
     * @var string
     */
    protected const PATTERN_REGEX_RESOURCE_ID = '/(?<=\{)[\w\-_]+?(?=\})/';

    /**
     * @var string
     */
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected const KEY_REQUIRED = 'required';

    /**
     * @var string
     */
    protected const KEY_REF = 'ref';

    /**
     * @var string
     */
    protected const KEY_DOCUMENTATION_REF = '$ref';

    /**
     * @var string
     */
    protected const PARAMETER_LOCATION_PATH = 'path';

    /**
     * @var string
     */
    protected const PARAMETER_SCHEMA_TYPE_STRING = 'string';

    /**
     * @var string
     */
    protected const PATTERN_PARAMETER_REFERENCE = '#/components/parameters/%s';

    /**
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(array $pathMethodData): array
    {
        $parameters = $this->addIdParametersFromPath($pathMethodData);

        if ($pathMethodData['parameters']) {
            $parameters = array_merge($parameters, $this->addPathParameterComponents($pathMethodData));
        }

        return $parameters;
    }

    /**
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    protected function addIdParametersFromPath(array $pathMethodData): array
    {
        $pathParameters = $this->getPathParametersFromResourcePath((string)key($pathMethodData));
        $parameters = [];

        foreach ($pathParameters as $pathParameter) {
            $parameterComponentData = [];
            $parameterComponentData['name'] = $pathParameter;
            $parameterComponentData['in'] = static::PARAMETER_LOCATION_PATH;
            $parameterComponentData['description'] = $this->getPathParameterDescription($pathParameter);
            $parameterComponentData['schemaType'] = static::PARAMETER_SCHEMA_TYPE_STRING;
            $parameterComponentData[static::KEY_REQUIRED] = true;

            $parameters[] = $this->addPathParameterComponent($parameterComponentData);
        }

        return $parameters;
    }

    /**
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    protected function addPathParameterComponents(array $pathMethodData): array
    {
        $parameters = [];

        foreach ($pathMethodData['parameters'] as $parameterComponentData) {
            $parameters[] = $this->addPathParameterComponent($parameterComponentData);
        }

        return $parameters;
    }

    /**
     * @param array<mixed> $parameterComponentData
     *
     * @return array<mixed>
     */
    protected function addPathParameterComponent(array $parameterComponentData): array
    {
        if (isset($parameterComponentData[static::KEY_REF])) {
            $parameterComponentData[static::KEY_DOCUMENTATION_REF] = sprintf(static::PATTERN_PARAMETER_REFERENCE, $parameterComponentData[static::KEY_REF]);
            unset($parameterComponentData[static::KEY_REF]);

            return $parameterComponentData;
        }

        if (!isset($parameterComponentData[static::KEY_REQUIRED])) {
            $parameterComponentData[static::KEY_REQUIRED] = false;
        }

        $parameterComponentData[static::KEY_SCHEMA] = [
            static::KEY_TYPE => static::PARAMETER_SCHEMA_TYPE_STRING,
        ];

        return $parameterComponentData;
    }

    /**
     * @param string $resourcePath
     *
     * @return array<mixed>
     */
    protected function getPathParametersFromResourcePath(string $resourcePath): array
    {
        preg_match_all(static::PATTERN_REGEX_RESOURCE_ID, $resourcePath, $matches);

        return $matches[0] ?? [];
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    protected function getPathParameterDescription(string $parameter): string
    {
        /** @var array<string> $pieces */
        $pieces = preg_split(static::PATTERN_REGEX_RESOURCE_ID, $parameter);

        $parameterSplitted = array_slice($pieces, 0, -1);
        $parameterSplitted = array_map('lcfirst', $parameterSplitted);

        return sprintf(static::PATTERN_REGEX_RESOURCE_ID, implode(' ', $parameterSplitted));
    }
}
