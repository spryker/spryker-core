<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetBanner\ContentWidgetConfigurationProvider;

class CmsContentWidgetBannerConfigurationProvider implements CmsContentWidgetBannerConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'cms_banner';

    public const CUSTOM_TEMPLATE_IDENTIFIER = 'custom';

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
            static::CUSTOM_TEMPLATE_IDENTIFIER => '@CmsContentWidgetBanner/views/cms-banner/cms-banner-custom.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ cms_banner({title: string, subTitle: string, imageUrl: string, clickUrl: string, altText: string}) }}. To use a different template {{ cms_banner({title: string, subTitle: string, imageUrl: string, clickUrl: string, altText: string}), 'default') }}.";
    }
}
