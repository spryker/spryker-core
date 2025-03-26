<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\Mapper;

interface DataExportMapperInterface
{
    /**
     * @param array<mixed> $rawData
     * @param array<string> $fieldMapping
     *
     * @return array<mixed>
     */
    public function mapDatabaseDataToExportFields(array $rawData, array $fieldMapping): array;

    /**
     * @param array<string> $pathKeys
     *
     * @return array<mixed>
     */
    public function extractFieldHeaders(array $pathKeys): array;
}
