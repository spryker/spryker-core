<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Symfony\Component\HttpFoundation\Response;

class PathPatchMethodBuilder extends AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_HTTP_METHOD_PATCH = 'patch';

    /**
     * @var string
     */
    protected const SUMMARY_UPDATE_COLLECTION = 'Update collection of %s';

    /**
     * @var string
     */
    protected const SUMMARY_UPDATE_ENTITY = 'Update entity of %s';

    /**
     * @var string
     */
    protected const PATH_UPDATE_COLLECTION_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const PATH_UPDATE_ENTITY_PLACEHOLDER = '/%s/%s/{id}';

    /**
     * @var string
     */
    protected const TPL_UPDATE_ENTITY_OPERATION_ID = 'update-entity-dynamic-api-%s';

    /**
     * @var string
     */
    protected const TPL_UPDATE_COLLECTION_OPERATION_ID = 'update-collection-dynamic-api-%s';

    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION = 'Expected response to a valid request returned successfully.';

    /**
     * @var string
     */
    protected const REQUEST_DATA_UPDATE_ENTITY_DESCRIPTION = 'Data to update entity.';

    /**
     * @var string
     */
    protected const REQUEST_DATA_UPDATE_COLLECTION_DESCRIPTION = 'Data to update collection of entities.';

    /**
     * @var string
     */
    protected const PROPERTY_NAME = 'data';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_PATHS => [
                $this->formatPath(static::PATH_UPDATE_COLLECTION_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_PATCH => $this->buildUpdateCollectionPathData($dynamicEntityConfigurationTransfer),
                ],
                $this->formatPath(static::PATH_UPDATE_ENTITY_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_PATCH => $this->buildUpdateEntityPathData($dynamicEntityConfigurationTransfer),
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpdateCollectionPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_UPDATE_COLLECTION_OPERATION_ID,
                static::SUMMARY_UPDATE_COLLECTION,
            ),
            $this->buildKeyParameters(),
            $this->buildRequestBody(
                static::REQUEST_DATA_UPDATE_COLLECTION_DESCRIPTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), false, false, true),
            ),
            $this->buildResponses($this->buildUpdateCollectionResponses($dynamicEntityConfigurationTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpdateEntityPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_UPDATE_ENTITY_OPERATION_ID,
                static::SUMMARY_UPDATE_ENTITY,
            ),
            $this->buildKeyParametersWithIdParameter($dynamicEntityConfigurationTransfer),
            $this->buildRequestBody(
                static::REQUEST_DATA_UPDATE_ENTITY_DESCRIPTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), true, false, true),
            ),
            $this->buildResponses($this->buildUpdateEntityResponses($dynamicEntityConfigurationTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpdateCollectionResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION,
                $this->prepareFieldsArray(
                    $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
                    false,
                    false,
                    true,
                ),
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
    protected function buildUpdateEntityResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION,
                $this->prepareFieldsArray(
                    $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
                    true,
                    false,
                    true,
                ),
                (string)Response::HTTP_OK,
            ),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseNotFound(),
            $this->buildResponseDefault(),
        );
    }
}
