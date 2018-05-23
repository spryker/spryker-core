<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FileManagerGui;

use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTypeTableMap;
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

    const COL_FILE_TYPE_ID_FILE_TYPE = SpyFileTypeTableMap::COL_ID_FILE_TYPE;
    const COL_FILE_TYPE_EXTENSION = SpyFileTypeTableMap::COL_EXTENSION;
    const COL_FILE_TYPE_IS_ALLOWED = SpyFileTypeTableMap::COL_IS_ALLOWED;

    const SORT_DESC = TableConfiguration::SORT_DESC;
    const ALLOWED_MIME_TYPES = 'ALLOWED_MIME_TYPES';
    const ERROR_MIME_TYPE_MESSAGE = 'Please upload a file with valid';
    const ERROR_FILE_MISSED_EDIT_MESSAGE = 'Upload a file or specify a new file name';
    const ERROR_FILE_MISSED_ADD_MESSAGE = 'Upload a file';
    const ERROR_FILE_NAME_MISSED_ADD_MESSAGE = 'Specify a file name or use real one';
    const MAX_FILE_SIZE = 'MAX_FILE_SIZE';
    const DEFAULT_MAX_FILE_SIZE = '10M';

    const FILE_MANAGER_GUI_VIEW_URL = '/file-manager-gui/view-file';
    const FILE_MANAGER_GUI_EDIT_URL = '/file-manager-gui/edit-file';
    const FILE_MANAGER_GUI_DELETE_URL = '/file-manager-gui/delete-file/file';
}
