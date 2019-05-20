<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentFileGui;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentFileGuiConfig extends AbstractSharedConfig
{
    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::CONTENT_TYPE_FILE_LIST
     *
     * Content item file list
     */
    public const CONTENT_TYPE_FILE_LIST = 'File List';

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::CONTENT_TERM_FILE_LIST
     *
     * Content item file list
     */
    public const CONTENT_TERM_FILE_LIST = 'File List';
}
