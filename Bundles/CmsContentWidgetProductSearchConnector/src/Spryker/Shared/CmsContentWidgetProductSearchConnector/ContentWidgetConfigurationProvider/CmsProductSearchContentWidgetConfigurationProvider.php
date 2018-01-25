<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetProductSearchConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsProductSearchContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    const FUNCTION_NAME = 'product_search';
    const TEMPLATE_PATH = '@Product/product/partials/product_search_cms_content_widget.twig';

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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => static::TEMPLATE_PATH,
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return <<<EOT
            {{ product_search('is-active:false AND locale:de_DE') }},
            to use different template {{ product_search('is-active:false AND locale:de_DE', 'default') }}
EOT;
    }
}
