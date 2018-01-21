<?php

namespace Spryker\Shared\FileManagerGui;

use Orm\Zed\Cms\Persistence\Map\SpyFileTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface FileManagerGuiConstants
{

    const COL_ID_FILE = SpyFileTableMap::COL_ID_FILE;
    const COL_FILE_NAME = SpyFileTableMap::COL_FILE_NAME;
    const COL_ACTIONS = 'Actions';

    const SORT_DESC = TableConfiguration::SORT_DESC;
}