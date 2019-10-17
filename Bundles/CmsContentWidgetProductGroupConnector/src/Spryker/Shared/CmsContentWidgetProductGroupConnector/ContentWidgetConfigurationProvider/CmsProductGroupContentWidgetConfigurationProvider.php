<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetProductGroupConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

/**
 * @deprecated Use `SprykerShop\Shared\CmsContentWidgetProductConnector\ContentWidgetConfigurationProvider\CmsProductGroupContentWidgetConfigurationProvider` instead.
 */
class CmsProductGroupContentWidgetConfigurationProvider implements CmsContentWidgetConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'product_group';

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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@ProductGroup/partials/product_group_cms_content_widget.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ product_group(['sku1', 'sku2']) }}, to use different template {{ product_group(['sku1', 'sku2'], 'default') }}";
    }
}
