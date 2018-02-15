<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FileWidget\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class FileWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    const FUNCTION_NAME = 'cmsFile';

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array
     */
    public function getAvailableTemplates()
    {
        return [
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@FileWidget/_file/cms-file-content-widget.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ cmsFile('fileId') }}";
    }
}
