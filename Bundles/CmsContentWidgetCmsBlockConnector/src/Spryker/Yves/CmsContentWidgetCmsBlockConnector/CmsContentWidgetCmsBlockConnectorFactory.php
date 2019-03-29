<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetCmsBlockConnector;

use Spryker\Yves\CmsContentWidgetCmsBlockConnector\Dependency\Client\CmsContentWidgetCmsBlockConnectorToCmsBlockStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class CmsContentWidgetCmsBlockConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidgetCmsBlockConnector\Dependency\Client\CmsContentWidgetCmsBlockConnectorToCmsBlockStorageClientInterface
     */
    public function getCmsBlockStorageClient(): CmsContentWidgetCmsBlockConnectorToCmsBlockStorageClientInterface
    {
        return $this->getProvidedDependency(CmsContentWidgetCmsBlockConnectorDependencyProvider::CLIENT_CMS_BLOCK_STORAGE);
    }
}
