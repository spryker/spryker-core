<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsCollector;

use Spryker\Client\CmsCollector\Zed\CmsCollectorStub;
use Spryker\Client\Kernel\AbstractFactory;

class CmsCollectorFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\CmsCollector\Zed\CmsCollectorStub
     */
    public function createCmsCollectorStub()
    {
        return new CmsCollectorStub($this->getProvidedDependency(CmsCollectorDependencyProvider::SERVICE_ZED));
    }

}
