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
     * @return array<string, mixed>
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
            $this->buildUpdateCollectionRequestBody($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildUpdateCollectionResponses($dynamicEntityConfigurationTransfer),
            ),
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
            $this->buildKeyParameters($dynamicEntityConfigurationTransfer),
            $this->buildUpdateEntityRequestBody($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildUpdateEntityResponses($dynamicEntityConfigurationTransfer),
            ),
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
            $this->buildUpdateCollectionSuccessResponseBody($dynamicEntityConfigurationTransfer),
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
            $this->buildUpdateEntitySuccessResponseBody($dynamicEntityConfigurationTransfer),
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
    protected function getEntityRequestDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf('%s %s', static::REQUEST_DATA_UPDATE_ENTITY_DESCRIPTION, $this->getRequestDescription($dynamicEntityConfigurationTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getCollectionRequestDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf('%s %s', static::REQUEST_DATA_UPDATE_COLLECTION_DESCRIPTION, $this->getRequestDescription($dynamicEntityConfigurationTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildUpdateEntityRequestBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = true;
        $filterIsCreated = false;
        $filterIsEditable = true;
        $requestDescription = $this->getEntityRequestDescription($dynamicEntityConfigurationTransfer);

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
            $this->prepareFieldsArrayRecursively(
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
    protected function buildUpdateCollectionRequestBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = false;
        $filterIsCreated = false;
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
            $this->prepareFieldsArrayRecursively(
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
    protected function buildUpdateEntitySuccessResponseBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = true;
        $filterIsCreated = false;
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
            $this->prepareFieldsArrayRecursively(
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
    protected function buildUpdateCollectionSuccessResponseBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
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
            $this->prepareFieldsArrayRecursively(
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
