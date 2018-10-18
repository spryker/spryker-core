<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;

class FileInfoViewTable extends FileInfoTable
{
    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks($item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate('/file-manager-gui/download-file', [
                static::REQUEST_ID_FILE_INFO => $item[static::COL_ID_FILE_INFO],
            ]),
            'Download'
        );

        return $buttons;
    }
}
