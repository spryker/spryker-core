<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetProductSearchConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsProductSearchContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'product_search';
    public const TEMPLATE_PATH = '@Product/product/partials/product_cms_content_widget.twig';

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
            Simple example: {{ product_search('is-active:false AND locale:de_DE') }}.
            Whole search query should be in one string and passed as first parameter.
            You should use `AND` in capital letters, otherwise ElasticSearch will mark it as part of searching text.
            To use different template, add it as second parameter:
            {{ product_search('is-active:false AND locale:de_DE', 'default') }}.
EOT;
    }
}
