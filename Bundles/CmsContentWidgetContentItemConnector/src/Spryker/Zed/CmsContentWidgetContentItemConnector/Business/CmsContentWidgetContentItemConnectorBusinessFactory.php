<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector\Business;

use Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapper;
use Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapperInterface;
use Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorDependencyProvider;
use Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\Persistence\CmsContentWidgetContentItemConnectorRepositoryInterface getRepository()
 */
class CmsContentWidgetContentItemConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetContentItemConnector\Business\Mapper\CmsContentItemKeyMapperInterface
     */
    public function createCmsContentItemKeyMapper(): CmsContentItemKeyMapperInterface
    {
        return new CmsContentItemKeyMapper($this->getContentStorageClient());
    }

    /**
     * @return \Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientInterface
     */
    public function getContentStorageClient(): CmsContentWidgetContentItemConnectorToContentStorageClientInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetContentItemConnectorDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
