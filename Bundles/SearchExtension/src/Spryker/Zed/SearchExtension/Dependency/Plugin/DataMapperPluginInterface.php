<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataMappingContextTransfer;

interface DataMapperPluginInterface
{
    /**
     * Specification:
     * - Maps raw data to search data within a given context.
     * - Returns data in a vendor specific format, that is suitable for search.
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
     * - Returns true, if a data mapper plugin can be used in a given context.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return bool
     */
    public function isApplicable(DataMappingContextTransfer $dataMappingContextTransfer): bool;
}
