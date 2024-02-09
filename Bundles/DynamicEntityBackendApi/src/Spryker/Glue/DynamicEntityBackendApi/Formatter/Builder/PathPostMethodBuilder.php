<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Symfony\Component\HttpFoundation\Response;

class PathPostMethodBuilder extends AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_HTTP_METHOD_POST = 'post';

    /**
     * @var string
     */
    protected const PATH_GET_COLLECTION_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const PROPERTY_NAME = 'data';

    /**
     * @var string
     */
    protected const SUMMARY_SAVE_COLLECTION = 'Save collection of %s';

    /**
     * @var string
     */
    protected const DESCRIPTION_REQUEST_BODY = 'Data to create new entities.';

    /**
     * @var string
     */
    protected const DESCRIPTION_RESPONSE_BODY = 'Created entities.';

    /**
     * @var string
     */
    protected const TPL_SAVE_COLLECTION_OPERATION_ID = 'save-collection-dynamic-api-%s';

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
                    static::KEY_HTTP_METHOD_POST => $this->buildSaveCollectionPathData($dynamicEntityConfigurationTransfer),
                ],

            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildSaveCollectionPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_SAVE_COLLECTION_OPERATION_ID,
                static::SUMMARY_SAVE_COLLECTION,
            ),
            $this->buildKeyParameters(),
            $this->buildSaveCollectionRequestBody(
                $dynamicEntityConfigurationTransfer,
            ),
            $this->buildResponses($this->buildSaveCollectionResponses($dynamicEntityConfigurationTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildSaveCollectionResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildSaveCollectionSuccessResponseBody($dynamicEntityConfigurationTransfer),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseDefault(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return string
     */
    protected function getSaveRequestDescription(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): string
    {
        return sprintf('%s %s', static::DESCRIPTION_REQUEST_BODY, $this->getRequestDescription($dynamicEntityConfigurationTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildSaveCollectionRequestBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = true;
        $filterIsCreated = true;
        $requestDescription = $this->getSaveRequestDescription($dynamicEntityConfigurationTransfer);

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildRequestBody(
                $requestDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                ),
                true,
                true,
            );
        }

        return $this->buildRequestBody(
            $requestDescription,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
            ),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildSaveCollectionSuccessResponseBody(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $skipIdentifier = false;
        $filterIsCreated = true;
        $httpCodeStatus = (string)Response::HTTP_CREATED;
        $responseSchemaDescription = static::DESCRIPTION_RESPONSE_BODY;
        $isCollection = true;

        if ($this->haveChildRelations($dynamicEntityConfigurationTransfer)) {
            return $this->buildSuccessResponse(
                $responseSchemaDescription,
                $this->buildOneOfCombinationArrayRecursively(
                    $dynamicEntityConfigurationTransfer,
                    $skipIdentifier,
                    $filterIsCreated,
                ),
                $httpCodeStatus,
                $isCollection,
                true,
            );
        }

        return $this->buildSuccessResponse(
            $responseSchemaDescription,
            $this->prepareFieldsArrayWithChilds(
                $dynamicEntityConfigurationTransfer,
                $skipIdentifier,
                $filterIsCreated,
            ),
            (string)Response::HTTP_CREATED,
            $isCollection,
        );
    }
}
