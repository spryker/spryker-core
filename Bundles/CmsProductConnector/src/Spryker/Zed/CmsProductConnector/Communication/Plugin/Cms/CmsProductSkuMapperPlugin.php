<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Communication\Plugin\Cms;

use Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsProductConnector\Business\CmsProductConnectorFacadeInterface getFacade()
 */
class CmsProductSkuMapperPlugin extends AbstractPlugin implements CmsContentWidgetParameterMapperPluginInterface
{

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function map(array $parameters)
    {
        return $this->getFacade()
            ->mapProductSkuList($parameters);
    }

}
