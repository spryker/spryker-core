<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper;

class FieldMapper
{
    /**
     * @param array $fields
     * @param array $exportData
     *
     * @return array
     */
    public function mapDataByField(array $fields, array $exportData): array
    {
        $exportData = array_map(function (array $exportRow) use ($fields): array {
            return array_merge(array_flip($fields), $exportRow);
        }, $exportData);

        return $exportData;
    }
}
