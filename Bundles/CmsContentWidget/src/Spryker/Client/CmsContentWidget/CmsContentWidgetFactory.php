<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsContentWidget;

use Spryker\Client\CmsContentWidget\Zed\CmsContentWidgetStub;
use Spryker\Client\Kernel\AbstractFactory;

class CmsContentWidgetFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\CmsContentWidget\Zed\CmsContentWidgetStub
     */
    public function createCmsContentWidgetStub()
    {
        return new CmsContentWidgetStub($this->getProvidedDependency(CmsContentWidgetDependencyProvider::SERVICE_ZED));
    }

}
