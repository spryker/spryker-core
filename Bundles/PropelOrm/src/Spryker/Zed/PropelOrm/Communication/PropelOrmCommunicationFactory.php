<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PropelOrm\PropelOrmDependencyProvider;

class PropelOrmCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PropelOrm\Dependency\Facade\PropelOrmToLogInterface
     */
    public function getLogFacade()
    {
        return $this->getProvidedDependency(PropelOrmDependencyProvider::FACADE_LOG);
    }
}
