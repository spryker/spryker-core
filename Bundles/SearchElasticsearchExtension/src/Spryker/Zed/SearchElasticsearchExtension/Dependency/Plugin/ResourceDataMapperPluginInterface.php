<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataMappingContextTransfer;

interface ResourceDataMapperPluginInterface
{
    /**
     * Specification:
     * - Maps raw resource data (product, cms page etc.) to search data within a given context.
     * - Returns a formatted representation of resource data, suitable for storing in Elasticsearch.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array;

    /**
     * Specification:
     * - Returns true, if a data mapper plugin is applicable for mapping in a given context.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return bool
     */
    public function isApplicable(DataMappingContextTransfer $dataMappingContextTransfer): bool;
}
