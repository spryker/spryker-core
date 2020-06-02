<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Communication\Plugin\Cms;

use Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\Business\CmsContentWidgetContentItemConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorConfig getConfig()
 */
class CmsContentItemKeyMapperPlugin extends AbstractPlugin implements CmsContentWidgetParameterMapperPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return array<string, string>
     *
     * @param string[] $parameters
     *
     * @return string[]
     */
    public function map(array $parameters)
    {
        return $this->getFacade()->mapContentItemKeyList($parameters);
    }
}
