<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StoreReference\Business\Reader\StoreReferenceReader;
use Spryker\Zed\StoreReference\Business\Reader\StoreReferenceReaderInterface;
use Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreInterface;
use Spryker\Zed\StoreReference\StoreReferenceDependencyProvider;

/**
 * @method \Spryker\Zed\StoreReference\StoreReferenceConfig getConfig()
 */
class StoreReferenceBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StoreReference\Business\Reader\StoreReferenceReaderInterface
     */
    public function createStoreReferenceMap(): StoreReferenceReaderInterface
    {
        return new StoreReferenceReader(
            $this->getConfig(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreReference\Dependency\Facade\StoreReferenceToStoreInterface
     */
    public function getStoreFacade(): StoreReferenceToStoreInterface
    {
        return $this->getProvidedDependency(StoreReferenceDependencyProvider::FACADE_STORE);
    }
}
