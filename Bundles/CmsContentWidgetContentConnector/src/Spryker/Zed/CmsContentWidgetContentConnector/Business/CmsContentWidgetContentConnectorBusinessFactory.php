<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentConnector\Business;

use Spryker\Zed\CmsContentWidgetContentConnector\Business\Mapper\CmsContentItemKeyMapper;
use Spryker\Zed\CmsContentWidgetContentConnector\Business\Mapper\CmsContentItemKeyMapperInterface;
use Spryker\Zed\CmsContentWidgetContentConnector\CmsContentWidgetContentConnectorDependencyProvider;
use Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade\CmsContentWidgetContentConnectorToContentFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentConnector\CmsContentWidgetContentConnectorConfig getConfig()
 */
class CmsContentWidgetContentConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetContentConnector\Business\Mapper\CmsContentItemKeyMapperInterface
     */
    public function createCmsContentItemKeyMapper(): CmsContentItemKeyMapperInterface
    {
        return new CmsContentItemKeyMapper($this->getContentFacade());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidgetContentConnector\Dependency\Facade\CmsContentWidgetContentConnectorToContentFacadeInterface
     */
    public function getContentFacade(): CmsContentWidgetContentConnectorToContentFacadeInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetContentConnectorDependencyProvider::FACADE_CONTENT);
    }
}
