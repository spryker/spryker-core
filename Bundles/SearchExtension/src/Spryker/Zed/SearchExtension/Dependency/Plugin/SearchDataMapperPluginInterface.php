<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataMappingContextTransfer;

interface SearchDataMapperPluginInterface
{
    /**
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array;

    /**
     * @api
     *
     * @param string $resourceType
     *
     * @return bool
     */
    public function isApplicable(string $resourceType): bool;
}
