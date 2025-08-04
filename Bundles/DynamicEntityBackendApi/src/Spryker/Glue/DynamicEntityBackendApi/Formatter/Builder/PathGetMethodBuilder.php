<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

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
    protected const TPL_COLLECTION_OPERATION_ID = 'get-collection-dynamic-api-%s';

    /**
     * @var string
     */
    protected const TPL_ENTITY_OPERATION_ID = 'get-entity-dynamic-api-%s';

    /**
     * @var string
     */
    protected const PAGE = 'page';

    /**
     * @var string
     */
    protected const QUERY = 'query';

    /**
     * @var string
     */
    protected const INCLUDE = 'include';

    /**
     * @var string
     */
    protected const TEMPLATE_EXAMPLE_INCLUDE = '%s (or %s)';

    /**
     * @var string
     */
    protected const TEMPLATE_INCLUDE_DESCRIPTION = 'Parameter is used to include related resources.
    Possible values are:`%s`.
    Use for `GET {{url}}/{{resource}}?include={{relation}},{{relation}} -> {200, { ...default+fields... + {{relation}},{{relation}} }}`.';

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
    protected function buildGetEntityPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
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
            $includeEnums[] = $childRelation->getNameOrFail();
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
                $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions(),
                $httpCodeStatus,
                $isCollection,
                true,
            );
        }

        return $this->buildSuccessResponse(
            $responseDescription,
            $this->prepareFieldsArrayWithChildren(
                $dynamicEntityConfigurationTransfer,
            ),
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getFieldDefinitions(),
            $httpCodeStatus,
            $isCollection,
        );
    }
}
