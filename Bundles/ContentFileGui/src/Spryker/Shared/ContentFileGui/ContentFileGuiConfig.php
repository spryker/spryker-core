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

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::TWIG_FUNCTION_NAME
     *
     * Content item file list function name
     */
    public const TWIG_FUNCTION_NAME = 'content_file_list';

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK
     *
     * Content item file list text-link template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK = 'text-link';

    /**
     * Content item file list text-link template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_TEXT_LINK = 'content_file_gui.template.text-link';

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE
     *
     * Content item file list file-icon-and-size template identifier
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE = 'file-icon-and-size';

    /**
     * Content item file list file-icon-and-size template name
     */
    protected const WIDGET_TEMPLATE_DISPLAY_NAME_FILE_ICON_AND_SIZE = 'content_file_gui.template.file-icon-and-size';

    /**
     * @return string[]
     */
    public function getContentWidgetTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK => static::WIDGET_TEMPLATE_DISPLAY_NAME_TEXT_LINK,
            static::WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE => static::WIDGET_TEMPLATE_DISPLAY_NAME_FILE_ICON_AND_SIZE,
        ];
    }

    /**
     * @return string
     */
    public function getTwigFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME;
    }
}
