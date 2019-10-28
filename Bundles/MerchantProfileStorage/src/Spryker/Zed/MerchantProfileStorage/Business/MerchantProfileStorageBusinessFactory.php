<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProfileStorage\Business\Storage\MerchantProfileStorageWriter;
use Spryker\Zed\MerchantProfileStorage\Business\Storage\MerchantProfileStorageWriterInterface;
use Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface;
use Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface getEntityManager()()
 */
class MerchantProfileStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProfileStorage\Business\Storage\MerchantProfileStorageWriterInterface
     */
    public function createMerchantProfileStorageWriter(): MerchantProfileStorageWriterInterface
    {
        return new MerchantProfileStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getMerchantProfileFacade(),
            $this->getLocaleFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToMerchantProfileFacadeInterface
     */
    public function getMerchantProfileFacade(): MerchantProfileStorageToMerchantProfileFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileStorageDependencyProvider::FACADE_MERCHANT_PROFILE);
    }

    /**
     * @return \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MerchantProfileStorageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileStorageDependencyProvider::FACADE_LOCALE);
    }
}
