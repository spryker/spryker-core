<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

class PathDeleteMethodBuilder extends AbstractPathMethodBuilder implements PathMethodBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_HTTP_METHOD_DELETE = 'delete';

    /**
     * @var string
     */
    protected const SUMMARY_DELETE_COLLECTION = 'Delete collection of %s';

    /**
     * @var string
     */
    protected const SUMMARY_DELETE_ENTITY = 'Delete entity of %s';

    /**
     * @var string
     */
    protected const PATH_DELETE_COLLECTION_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const PATH_DELETE_ENTITY_PLACEHOLDER = '/%s/%s/{id}';

    /**
     * @var string
     */
    protected const TPL_DELETE_ENTITY_OPERATION_ID = 'delete-entity-dynamic-api-%s';

    /**
     * @var string
     */
    protected const TPL_DELETE_COLLECTION_OPERATION_ID = 'delete-collection-dynamic-api-%s';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    public function buildPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        if ((bool)$dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIsDeletable() !== true) {
            return [];
        }

        return [
            static::KEY_PATHS => [
                $this->formatPath(static::PATH_DELETE_COLLECTION_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_DELETE => $this->buildDeleteCollectionPathData($dynamicEntityConfigurationTransfer),
                ],
                $this->formatPath(static::PATH_DELETE_ENTITY_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()) => [
                    static::KEY_HTTP_METHOD_DELETE => $this->buildDeleteEntityPathData($dynamicEntityConfigurationTransfer),
                ],
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildDeleteCollectionPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_DELETE_COLLECTION_OPERATION_ID,
                static::SUMMARY_DELETE_COLLECTION,
            ),
            [
                static::KEY_PARAMETERS => array_merge([$this->buildFilterParameter($dynamicEntityConfigurationTransfer)], $this->buildKeyParameters()[static::KEY_PARAMETERS]),
            ],
            $this->buildResponses(
                $this->buildDeleteCollectionResponses($dynamicEntityConfigurationTransfer),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildDeleteEntityPathData(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->expandPathData(
                $dynamicEntityConfigurationTransfer,
                static::TPL_DELETE_ENTITY_OPERATION_ID,
                static::SUMMARY_DELETE_ENTITY,
            ),
            $this->buildKeyParameters($dynamicEntityConfigurationTransfer),
            $this->buildResponses(
                $this->buildDeleteEntityResponses($dynamicEntityConfigurationTransfer),
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildDeleteCollectionResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseNoContent(),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseMethodNotAllowed(),
            $this->buildResponseDefault(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function buildDeleteEntityResponses(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        return array_replace(
            $this->buildResponseNoContent(),
            $this->buildResponseUnauthorizedRequest(),
            $this->buildResponseMethodNotAllowed(),
            $this->buildResponseDefault(),
        );
    }
}
