<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Symfony\Component\HttpFoundation\Response;

class PathGetMethodBuilder extends AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_HTTP_METHOD_GET = 'get';

    /**
     * @var string
     */
    protected const KEY_PARAMETER_ENUM = 'enum';

    /**
     * @var string
     */
    protected const PATH_GET_COLLECTION_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const PATH_GET_ENTITY_PLACEHOLDER = '/%s/%s/{id}';

    /**
     * @var string
     */
    protected const SUMMARY_GET_COLLECTION = 'Get collection of entities for defined resource.';

    /**
     * @var string
     */
    protected const SUMMARY_GET_ITEM = 'Get item of entities for defined resource.';

    /**
     * @var string
     */
    protected const PARAMETER_FILTER_DESCRIPTION = 'Parameter is used to filter items by specified values.';

    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION_GET_COLLECTION = 'Expected response to a valid request returned successfully.';

    /**
     * @var string
     */
    protected const PARAMETER_INCLUDE_DESCRIPTION = 'Parameter is used to include related entities.';

    /**
     * @var string
     */
    protected const TPL_COLLECTION_OPERATION_ID = 'get-collection-dynamic-api-%s';

    /**
     * @var string
     */
    protected const TPL_ENTITY_OPERATION_ID = 'get-entity-dynamic-api-%s';

    /**
     * @var string
     */
    protected const QUERY = 'query';

    /**
     * @var string
     */
    protected const FILTER = 'filter';

    /**
     * @var string
     */
    protected const PAGE = 'page';

    /**
     * @var string
     */
    protected const INCLUDE = 'include';

    /**
     * @var string
     */
    protected const DEEP_OBJECT = 'deepObject';

    /**
     * @var string
     */
    protected const TEMPLATE_EXAMPLE_INCLUDE = '%s (or %s)';

    /**
     * @var string
     */
    protected const TEMPLATE_INCLUDE_DESCRIPTION = 'Parameter is used to include related resources.
            Possible values are: %s. Use for `GET {{url}}/{{resource}}?include={{relation}}-> {200, { ...default+fields... + {{relation}} }}`.
            It works also in deep relations like `{{url}}/{{resource}}?include={{relation}}.{{relation}}-> {200, { ...default+fields... + {{relation}}: { ...default+fields... + {{relation}}: { ...default+fields... } } }}`.
            Examples: ';

    /**
     * @var string
     */
    protected const TEMPLATE_INCLUDE_ADDITIONAL_DESCRIPTION = ' `{{url}}/%s?include=%s-> {200, { ...default_fields... + %s: {...relation_fields...} }}`;  ';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_PATHS => [
                $this->formatPath(static::PATH_GET_COLLECTION_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_GET => $this->buildGetCollectionPathData($dynamicEntityConfigurationTransfer),
                ],
                $this->formatPath(static::PATH_GET_ENTITY_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_GET => $this->buildGetEntityPathData($dynamicEntityConfigurationTransfer),
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildGetCollectionPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_COLLECTION_OPERATION_ID,
                static::SUMMARY_GET_COLLECTION,
            ),
            $this->buildKeyParametersWithFilterParameter($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildCollectionResponses($dynamicEntityConfigurationTransfer),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildCollectionResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildGetSuccessResponseBody(
                $dynamicEntityConfigurationTransfer,
                static::SUMMARY_GET_ITEM,
                true,
            ),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseDefault(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildGetEntityPathData(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): array {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_ENTITY_OPERATION_ID,
                static::SUMMARY_GET_ITEM,
            ),
            $this->buildKeyParameters($dynamicEntityConfigurationTransfer),
            [static::KEY_RESPONSES => $this->buildGetEntityResponse($dynamicEntityConfigurationTransfer)],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildGetEntityResponse(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildGetSuccessResponseBody(
                $dynamicEntityConfigurationTransfer,
                static::SUMMARY_GET_ITEM,
            ),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseNotFound(),
            $this->buildResponseDefault(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildFilterParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $resourceName = $dynamicEntityConfigurationTransfer->getTableAliasOrFail();

        $properties = [];
        foreach ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions() as $field) {
            $propertyKey = sprintf('%s.%s', $resourceName, $field->getFieldVisibleNameOrFail());
            $properties[$propertyKey] = $this->buildKeyType($field->getTypeOrFail());
        }

        return [
            static::KEY_NAME => static::FILTER,
            static::KEY_IN => static::QUERY,
            static::KEY_DESCRIPTION => static::PARAMETER_FILTER_DESCRIPTION,
            static::KEY_REQUIRED => false,
            static::KEY_STYLE => static::DEEP_OBJECT,
            static::KEY_EXPLODE => true,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                static::KEY_SCHEMA_PROPERTIES => $properties,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildPageParameter(): array
    {
        return [
            static::KEY_NAME => static::PAGE,
            static::KEY_IN => static::QUERY,
            static::KEY_DESCRIPTION => 'Parameter is used to paginate items.',
            static::KEY_REQUIRED => false,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_OBJECT,
                static::KEY_SCHEMA_PROPERTIES => [
                    static::SCHEMA_OFFSET => [static::KEY_TYPE => static::SCHEMA_TYPE_INTEGER],
                    static::SCHEMA_LIMIT => [static::KEY_TYPE => static::SCHEMA_TYPE_INTEGER],
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildIncludeParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $includeParameterFormattedData = [
            static::KEY_NAME => static::INCLUDE,
            static::KEY_IN => static::QUERY,
            static::KEY_DESCRIPTION => $this->buildIncludeParameterDescription($dynamicEntityConfigurationTransfer),
            static::KEY_REQUIRED => false,
            static::KEY_SCHEMA => [
                static::KEY_TYPE => static::SCHEMA_TYPE_STRING,
                static::KEY_EXAMPLE => $this->buildIncludeParameterExample($dynamicEntityConfigurationTransfer),
            ],
        ];

        $enums = $this->buildIncludeParameterEnum($dynamicEntityConfigurationTransfer);

        if (is_array($includeParameterFormattedData[static::KEY_SCHEMA]) && $enums !== []) {
            $includeParameterFormattedData[static::KEY_SCHEMA][static::KEY_PARAMETER_ENUM] = $enums;
        }

        return $includeParameterFormattedData;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function buildIncludeParameterDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        $description = sprintf(
            static::TEMPLATE_INCLUDE_DESCRIPTION,
            implode(', ', $this->buildIncludeParameterEnum($dynamicEntityConfigurationTransfer)),
        );

        /**
         * @var \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $childRelation
         */
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $description .= sprintf(
                static::TEMPLATE_INCLUDE_ADDITIONAL_DESCRIPTION,
                $dynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                $childRelation->getName(),
                $childRelation->getName(),
            );
        }

        return $description;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string>
     */
    protected function buildIncludeParameterEnum(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $includeEnums = [];

        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $includeEnums = $includeEnums + $this->buildIncludePath($childRelation);
        }

        return $includeEnums;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function buildIncludeParameterExample(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        $includeEnums = $this->buildIncludeParameterEnum($dynamicEntityConfigurationTransfer);

        if ($includeEnums === []) {
            return '';
        }

        $firstIncludeRelation = array_shift($includeEnums);

        if ($includeEnums === []) {
            return $firstIncludeRelation;
        }

        return sprintf(static::TEMPLATE_EXAMPLE_INCLUDE, $firstIncludeRelation, implode(',', $includeEnums));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
     * @param string|null $parentRelationName
     *
     * @return array<string>
     */
    protected function buildIncludePath(
        DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer,
        ?string $parentRelationName = null
    ): array
    {
        $rootPath = $dynamicEntityConfigurationRelationTransfer->getNameOrFail();
        $pathExamples = [
            $rootPath,
        ];

        $dynamicEntityConfigurationTransfer = $dynamicEntityConfigurationRelationTransfer->getChildDynamicEntityConfigurationOrFail();
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childDynamicEntityConfigurationRelationTransfer) {
            if ($childDynamicEntityConfigurationRelationTransfer->getNameOrFail() === $parentRelationName) {
                continue;
            }

            $pathExamples = $this->populatePathExamples(
                $pathExamples,
                $this->buildIncludePath($childDynamicEntityConfigurationRelationTransfer, $dynamicEntityConfigurationRelationTransfer->getNameOrFail()),
                $rootPath,
            );
        }

        return array_unique($pathExamples);
    }

    /**
     * @param array<string> $pathExamples
     * @param array<string> $subPaths
     * @param string $rootPath
     *
     * @return array<string>
     */
    protected function populatePathExamples(array $pathExamples, array $subPaths, string $rootPath): array
    {
        foreach ($subPaths as $subPath) {
            $pathExamples[] = $rootPath . '.' . $subPath;
        }

        return array_unique($pathExamples);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildKeyParametersWithFilterParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $parameters = [
            static::KEY_PARAMETERS => [
                $this->buildFilterParameter($dynamicEntityConfigurationTransfer),
                $this->buildHeaderContentTypeParameter(),
                $this->buildHeaderAcceptParameter(),
                $this->buildPageParameter(),
            ],
        ];

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            $parameters[static::KEY_PARAMETERS][] = $this->buildIncludeParameter($dynamicEntityConfigurationTransfer);
        }

        return $parameters;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $responseDescription
     * @param bool $isCollection
     *
     * @return array<mixed>
     */
    protected function buildGetSuccessResponseBody(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $responseDescription,
        bool $isCollection = false
    ): array {
        $httpCodeStatus = (string)Response::HTTP_OK;

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildSuccessResponse(
                $responseDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                ),
                $httpCodeStatus,
                $isCollection,
                true,
            );
        }

        return $this->buildSuccessResponse(
            $responseDescription,
            $this->prepareFieldsArrayRecursively(
                $dynamicEntityConfigurationTransfer,
            ),
            $httpCodeStatus,
            $isCollection,
        );
    }
}
