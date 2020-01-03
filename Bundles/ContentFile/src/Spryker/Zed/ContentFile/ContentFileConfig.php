<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFile;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentFileConfig extends AbstractBundleConfig
{
    protected const MAX_NUMBER_FILES_IN_FILE_LIST = 20;

    /**
     * @return int
     */
    public function getMaxFilesInFileList(): int
    {
        return static::MAX_NUMBER_FILES_IN_FILE_LIST;
    }
}
