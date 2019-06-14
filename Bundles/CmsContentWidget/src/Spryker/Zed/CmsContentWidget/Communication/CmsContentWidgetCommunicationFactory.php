<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Communication;

use Spryker\Zed\CmsContentWidget\CmsContentWidgetDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig getConfig()
 * @method \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacadeInterface getFacade()
 */
class CmsContentWidgetCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidget\Dependency\Service\CmsContentWidgetToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(CmsContentWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
