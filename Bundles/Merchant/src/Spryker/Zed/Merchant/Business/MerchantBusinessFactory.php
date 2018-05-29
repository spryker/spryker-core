<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGenerator;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface;
use Spryker\Zed\Merchant\Business\Model\MerchantReader;
use Spryker\Zed\Merchant\Business\Model\MerchantReaderInterface;
use Spryker\Zed\Merchant\Business\Model\MerchantWriter;
use Spryker\Zed\Merchant\Business\Model\MerchantWriterInterface;
use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface;
use Spryker\Zed\Merchant\MerchantDependencyProvider;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Merchant\Business\Model\MerchantWriterInterface
     */
    public function createMerchantWriter(): MerchantWriterInterface
    {
        return new MerchantWriter(
            $this->getEntityManager(),
            $this->createMerchantKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Model\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface
     */
    public function createMerchantKeyGenerator(): MerchantKeyGeneratorInterface
    {
        return new MerchantKeyGenerator(
            $this->getRepository(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface
     */
    public function getUtilTextService(): MerchantToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
