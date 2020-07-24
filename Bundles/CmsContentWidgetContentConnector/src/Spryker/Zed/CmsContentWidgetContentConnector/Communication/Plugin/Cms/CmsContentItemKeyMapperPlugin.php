<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Communication\Plugin\Cms;

use Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentConnector\Business\CmsContentWidgetContentConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidgetContentConnector\CmsContentWidgetContentConnectorConfig getConfig()
 */
class CmsContentItemKeyMapperPlugin extends AbstractPlugin implements CmsContentWidgetParameterMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps given content item keys to corresponding persistent item keys.
     *
     * @api
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $contentItemKeys
     *
     * @return string[]
     */
    public function map(array $contentItemKeys)
    {
        return $this->getFacade()->mapContentItemKeys($contentItemKeys);
    }
}
