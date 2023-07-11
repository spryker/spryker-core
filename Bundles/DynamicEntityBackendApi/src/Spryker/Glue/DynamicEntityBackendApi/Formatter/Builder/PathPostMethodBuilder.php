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
     * @return array<mixed>
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
            $this->buildRequestBody(
                static::DESCRIPTION_REQUEST_BODY,
                $this->prepareFieldsArray($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(), true, true),
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
            $this->buildResponseSuccess(
                static::DESCRIPTION_RESPONSE_BODY,
                $this->prepareFieldsArray(
                    $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
                    false,
                    true,
                ),
                (string)Response::HTTP_CREATED,
            ),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseDefault(),
        );
    }
}
