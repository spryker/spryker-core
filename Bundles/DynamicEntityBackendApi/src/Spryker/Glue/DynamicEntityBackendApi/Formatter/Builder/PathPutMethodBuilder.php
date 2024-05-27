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
     * @return array<string, mixed>
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
            $this->buildUpsertCollectionRequestBody($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildUpsertCollectionResponses($dynamicEntityConfigurationTransfer),
            ),
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
            $this->buildKeyParameters($dynamicEntityConfigurationTransfer),
            $this->buildUpsertEntityRequestBody($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildUpsertEntityResponses($dynamicEntityConfigurationTransfer),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertEntityRequestBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = true;
        $filterIsCreated = true;
        $filterIsEditable = true;

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildRequestBody(
                static::REQUEST_DATA_UPSERT_ENTITY_DESCRIPTION,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                    $filterIsEditable,
                ),
                false,
                true,
            );
        }

        return $this->buildRequestBody(
            static::REQUEST_DATA_UPSERT_ENTITY_DESCRIPTION,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
                $filterIsEditable,
            ),
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
            $this->buildUpsertCollectionSuccessResponseBody($dynamicEntityConfigurationTransfer),
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
            $this->buildUpsertEntitySuccessResponseBody($dynamicEntityConfigurationTransfer),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseNotFound(),
            $this->buildResponseDefault(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getCollectionRequestDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf('%s %s', static::REQUEST_DATA_UPSERT_COLLECTION_DESCRIPTION, $this->getRequestDescription($dynamicEntityConfigurationTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertCollectionRequestBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = false;
        $filterIsCreated = true;
        $filterIsEditable = true;
        $requestDescription = $this->getCollectionRequestDescription($dynamicEntityConfigurationTransfer);

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildRequestBody(
                $requestDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                    $filterIsEditable,
                ),
                false,
                true,
            );
        }

        return $this->buildRequestBody(
            $requestDescription,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
                $filterIsEditable,
            ),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertEntitySuccessResponseBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = true;
        $filterIsCreated = true;
        $filterIsEditable = true;
        $httpCodeStatus = (string)Response::HTTP_OK;
        $responseDescription = static::RESPONSE_DESCRIPTION;

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildSuccessResponse(
                $responseDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                    $filterIsEditable,
                ),
                $httpCodeStatus,
                false,
                true,
            );
        }

        return $this->buildSuccessResponse(
            $responseDescription,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
                $filterIsEditable,
            ),
            $httpCodeStatus,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpsertCollectionSuccessResponseBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = false;
        $filterIsCreated = true;
        $filterIsEditable = true;
        $httpCodeStatus = (string)Response::HTTP_OK;
        $responseDescription = static::RESPONSE_DESCRIPTION;

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildSuccessResponse(
                $responseDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                    $filterIsEditable,
                ),
                $httpCodeStatus,
                false,
                true,
            );
        }

        return $this->buildSuccessResponse(
            $responseDescription,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
                $filterIsEditable,
            ),
            $httpCodeStatus,
            true,
        );
    }
}
