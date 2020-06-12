<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business;

use Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapper;
use Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapperInterface;
use Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorDependencyProvider;
use Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade\CmsContentWidgetContentItemConnectorToContentFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorConfig getConfig()
 */
class CmsContentWidgetContentItemConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapperInterface
     */
    public function createCmsContentItemKeyMapper(): CmsContentItemKeyMapperInterface
    {
        return new CmsContentItemKeyMapper($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Facade\CmsContentWidgetContentItemConnectorToContentFacadeInterface
     */
    public function getContentFacade(): CmsContentWidgetContentItemConnectorToContentFacadeInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetContentItemConnectorDependencyProvider::FACADE_CONTENT);
    }
}
