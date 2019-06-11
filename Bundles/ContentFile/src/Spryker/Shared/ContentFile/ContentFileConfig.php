<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentFile;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ContentFileConfig extends AbstractSharedConfig
{
    /**
     * Content item file list
     */
    public const CONTENT_TYPE_FILE_LIST = 'File List';

    /**
     * Content item file list
     */
    public const CONTENT_TERM_FILE_LIST = 'File List';

    /**
     * Content item file list function name
     */
    public const TWIG_FUNCTION_NAME = 'content_file_list';

    /**
     * Content item file list text-link template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK = 'text-link';

    /**
     * Content item file list file-icon-and-size template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE = 'file-icon-and-size';
}
