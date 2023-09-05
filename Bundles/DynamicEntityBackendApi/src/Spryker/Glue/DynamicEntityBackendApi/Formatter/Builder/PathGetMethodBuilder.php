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
    protected const DEEP_OBJECT = 'deepObject';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
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
            $this->buildResponses($this->buildCollectionResponses($dynamicEntityConfigurationTransfer)),
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
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION_GET_COLLECTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()),
                (string)Response::HTTP_OK,
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
            $this->buildKeyParametersWithIdParameter($dynamicEntityConfigurationTransfer),
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
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION_GET_COLLECTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()),
                (string)Response::HTTP_OK,
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
    protected function buildKeyParametersWithFilterParameter(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_PARAMETERS => [
                $this->buildFilterParameter($dynamicEntityConfigurationTransfer),
                $this->buildHeaderContentTypeParameter(),
                $this->buildHeaderAcceptParameter(),
                $this->buildPageParameter(),
            ],
        ];
    }
}
