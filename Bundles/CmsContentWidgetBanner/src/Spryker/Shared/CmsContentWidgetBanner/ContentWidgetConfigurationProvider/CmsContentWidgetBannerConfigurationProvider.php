<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetBanner\ContentWidgetConfigurationProvider;

class CmsContentWidgetBannerConfigurationProvider implements CmsContentWidgetBannerConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'cms_banner';

    public const TITLE_BOTTOM_TEMPLATE_IDENTIFIER = 'title-bottom';

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::FUNCTION_NAME;
    }

    /**
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            static::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetBanner/views/cms-banner/cms-banner.twig',
            static::TITLE_BOTTOM_TEMPLATE_IDENTIFIER => '@CmsContentWidgetBanner/views/cms-banner/cms-banner-title-bottom.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ cms_banner({title: string, subtitle: string, imageUrl: string, clickUrl: string, altText: string}) }}. 
            To use a different template 
            {{ cms_banner({title: string, subtitle: string, imageUrl: string, clickUrl: string, altText: string}), 'default') }}.";
    }
}
