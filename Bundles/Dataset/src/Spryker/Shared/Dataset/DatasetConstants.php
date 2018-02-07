<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Dataset;

use Orm\Zed\Dataset\Persistence\Map\SpyDatasetTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface DatasetConstants
{
    /**
     * Specification
     * - Defines column names for dataset
     *
     * @api
     */
    const RESOURCE_TYPE_DATASET = 'dataset';

    const COL_ID_DATASET = SpyDatasetTableMap::COL_ID_DATASET;
    const COL_DATASET_NAME = SpyDatasetTableMap::COL_NAME;
    const COL_ACTIONS = 'Actions';
    const COL_DATASET_INFO_CREATED_AT = SpyDatasetTableMap::COL_CREATED_AT;
    const SORT_DESC = TableConfiguration::SORT_DESC;
    const COL_IS_ACTIVE = SpyDatasetTableMap::COL_IS_ACTIVE;
}
