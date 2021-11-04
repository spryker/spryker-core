<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReader;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReaderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriter;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriterInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGenerator;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReader;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReaderInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriter;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\MerchantRelationshipSalesOrderThresholdRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig getConfig()
 */
class MerchantRelationshipSalesOrderThresholdBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface
     */
    public function getSalesOrderThresholdFacade(): MerchantRelationshipSalesOrderThresholdToSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDependencyProvider::FACADE_SALES_ORDER_THRESHOLD);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MerchantRelationshipSalesOrderThresholdToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Dependency\Facade\MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantRelationshipSalesOrderThresholdToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReaderInterface
     */
    public function createMerchantRelationshipThresholdReader(): MerchantRelationshipThresholdReaderInterface
    {
        return new MerchantRelationshipThresholdReader(
            $this->getRepository(),
            $this->createMerchantRelationshipSalesOrderThresholdTranslationReader(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriterInterface
     */
    public function createMerchantRelationshipThresholdWriter(): MerchantRelationshipThresholdWriterInterface
    {
        return new MerchantRelationshipThresholdWriter(
            $this->getSalesOrderThresholdFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createMerchantRelationshipSalesOrderThresholdGlossaryKeyGenerator(),
            $this->createMerchantRelationshipSalesOrderThresholdTranslationWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface
     */
    public function createMerchantRelationshipSalesOrderThresholdGlossaryKeyGenerator(): MerchantRelationshipSalesOrderThresholdGlossaryKeyGeneratorInterface
    {
        return new MerchantRelationshipSalesOrderThresholdGlossaryKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationReaderInterface
     */
    public function createMerchantRelationshipSalesOrderThresholdTranslationReader(): MerchantRelationshipSalesOrderThresholdTranslationReaderInterface
    {
        return new MerchantRelationshipSalesOrderThresholdTranslationReader(
            $this->getGlossaryFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\Translation\MerchantRelationshipSalesOrderThresholdTranslationWriterInterface
     */
    public function createMerchantRelationshipSalesOrderThresholdTranslationWriter(): MerchantRelationshipSalesOrderThresholdTranslationWriterInterface
    {
        return new MerchantRelationshipSalesOrderThresholdTranslationWriter(
            $this->getGlossaryFacade(),
        );
    }
}
