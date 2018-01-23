<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FileManagerGui;

use Orm\Zed\Cms\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyFileTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface FileManagerGuiConstants
{
    const COL_ID_FILE = SpyFileTableMap::COL_ID_FILE;
    const COL_FILE_NAME = SpyFileTableMap::COL_FILE_NAME;
    const COL_ACTIONS = 'Actions';

    const COL_ID_FILE_INFO = SpyFileInfoTableMap::COL_ID_FILE_INFO;
    const COL_FILE_INFO_VERSION_NAME = SpyFileInfoTableMap::COL_VERSION_NAME;
    const COL_FILE_INFO_TYPE = SpyFileInfoTableMap::COL_TYPE;
    const COL_FILE_INFO_CREATED_AT = SpyFileInfoTableMap::COL_CREATED_AT;

    const SORT_DESC = TableConfiguration::SORT_DESC;
}
