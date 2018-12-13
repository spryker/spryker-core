<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsContentWidgetCmsBlockConnector\ContentWidgetConfigurationProvider;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;

class CmsContentWidgetCmsBlockConnectorConfigurationProvider implements CmsContentWidgetCmsBlockConnectorConfigurationProviderInterface
{
    public const FUNCTION_NAME = 'cms_block';

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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetCmsBlockConnector/views/cms-block.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation()
    {
        return "{{ cms_block('block_name_placeholder') }}, to use different template {{ cms_block('block_name_placeholder', 'default') }}. Warning: Please avoid recursion. When editing Block A the user should not add the same Block A using a widget";
    }
}
