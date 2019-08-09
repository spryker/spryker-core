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
            CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER => '@CmsContentWidgetCmsBlockConnector/views/cms-block/cms-block.twig',
        ];
    }

    /**
     * @return string
     */
    public function getUsageInformation(): string
    {
        return "{{ cms_block(['Block Name 1', 'Block Name 2', 'Block Name 3']) }}. To use a different template, insert {{ cms_block(['Block Name 1', 'Block Name 2', 'Block Name 3'], 'default') }}. Warning: Please avoid situations involving loops. For example, when a Block 'A' is added to a Block 'A' your page will crash after a long period of waiting. This situation also exists if a Block 'A' references a Block 'B', which itself refers to a Block 'A'.";
    }
}
