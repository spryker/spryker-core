<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetChartConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsChartContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    const FUNCTION_NAME = 'chart';

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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@Chart/chart/partials/chart_cms_content_widget.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ chart(['plugin-name', 'data-identifier']) }}, to use different template {{ chart(['plugin-name', 'data-identifier'], 'default') }}";
    }
}
