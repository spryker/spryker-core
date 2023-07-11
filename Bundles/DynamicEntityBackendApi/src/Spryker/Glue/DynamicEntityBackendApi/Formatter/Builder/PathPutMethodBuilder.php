<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Symfony\Component\HttpFoundation\Response;

class PathPutMethodBuilder extends AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_HTTP_METHOD_PUT = 'put';

    /**
     * @var string
     */
    protected const PATH_UPSERT_COLLECTION_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const PATH_UPSERT_ENTITY_PLACEHOLDER = '/%s/%s/{id}';

    /**
     * @var string
     */
    protected const SUMMARY_UPSERT_ENTITY = 'Upsert entity of %s';

    /**
     * @var string
     */
    protected const SUMMARY_UPSERT_COLLECTION = 'Upsert collection of %s entities';

    /**
     * @var string
     */
    protected const PROPERTY_NAME = 'data';

    /**
     * @var string
     */
    protected const TPL_UPSERT_COLLECTION_OPERATION_ID = 'upsert-collection-dynamic-api-%s';

    /**
     * @var string
     */
    protected const TPL_UPSERT_ENTITY_OPERATION_ID = 'upsert-entity-dynamic-api-%s';

    /**
     * @var string
     */
    protected const RESPONSE_DESCRIPTION = 'Expected response to a valid request returned successfully.';

    /**
     * @var string
     */
    protected const REQUEST_DATA_UPSERT_ENTITY_DESCRIPTION = 'Data to create new or update entity.';

    /**
     * @var string
     */
    protected const REQUEST_DATA_UPSERT_COLLECTION_DESCRIPTION = 'Data to create new or update collection of entities.';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return [
            static::KEY_PATHS => [
                $this->formatPath(static::PATH_UPSERT_COLLECTION_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_PUT => $this->buildUpsertCollectionPathData($dynamicEntityConfigurationTransfer),
                ],
                $this->formatPath(static::PATH_UPSERT_ENTITY_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_PUT => $this->buildUpsertEntityPathData($dynamicEntityConfigurationTransfer),
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertCollectionPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_UPSERT_COLLECTION_OPERATION_ID,
                static::SUMMARY_UPSERT_COLLECTION,
            ),
            $this->buildKeyParameters(),
            $this->buildRequestBody(
                static::REQUEST_DATA_UPSERT_COLLECTION_DESCRIPTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), false, true, true),
            ),
            $this->buildResponses($this->buildUpsertCollectionResponses($dynamicEntityConfigurationTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function buildUpsertEntityPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_UPSERT_ENTITY_OPERATION_ID,
                static::SUMMARY_UPSERT_ENTITY,
            ),
            $this->buildKeyParametersWithIdParameter($dynamicEntityConfigurationTransfer),
            $this->buildRequestBody(
                static::REQUEST_DATA_UPSERT_ENTITY_DESCRIPTION,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), true, true, true),
            ),
            $this->buildResponses($this->buildUpsertEntityResponses($dynamicEntityConfigurationTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertCollectionResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION,
                $this->prepareFieldsArray(
                    $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
                    false,
                    true,
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
    protected function buildUpsertEntityResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseSuccess(
                static::RESPONSE_DESCRIPTION,
                $this->prepareFieldsArray(
                    $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
                    true,
                    true,
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
