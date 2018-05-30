<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FileManagerGui;

interface FileManagerGuiConstants
{
    const MAX_FILE_SIZE = 'MAX_FILE_SIZE';
    const DEFAULT_MAX_FILE_SIZE = '10M';

    const FILE_MANAGER_GUI_VIEW_URL = '/file-manager-gui/view-file';
    const FILE_MANAGER_GUI_EDIT_URL = '/file-manager-gui/edit-file';
    const FILE_MANAGER_GUI_DELETE_URL = '/file-manager-gui/delete-file/file';

    const ERROR_MIME_TYPE_MESSAGE = 'File type is not allowed for uploading';
    const ERROR_FILE_MISSED_EDIT_MESSAGE = 'Upload a file or specify a new file name';
    const ERROR_FILE_MISSED_ADD_MESSAGE = 'Upload a file';
    const ERROR_FILE_NAME_MISSED_ADD_MESSAGE = 'Specify a file name or use real one';
}
