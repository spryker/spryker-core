<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReader;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReaderInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriter;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriterInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGenerator;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationReader;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationReaderInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriter;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriterInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
     */
    public function getMinimumOrderValueFacade(): MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDependencyProvider::FACADE_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade(): MerchantRelationshipMinimumOrderValueToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToStoreFacadeInterface
     */
    protected function getStoreFacade(): MerchantRelationshipMinimumOrderValueToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdReaderInterface
     */
    public function createMerchantRelationshipThresholdReader(): MerchantRelationshipThresholdReaderInterface
    {
        return new MerchantRelationshipThresholdReader(
            $this->getRepository(),
            $this->createMerchantRelationshipMinimumOrderValueTranslationReader()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold\MerchantRelationshipThresholdWriterInterface
     */
    public function createMerchantRelationshipThresholdWriter(): MerchantRelationshipThresholdWriterInterface
    {
        return new MerchantRelationshipThresholdWriter(
            $this->getMinimumOrderValueFacade(),
            $this->getEntityManager(),
            $this->createMerchantRelationshipMinimumOrderValueGlossaryKeyGenerator(),
            $this->createMerchantRelationshipMinimumOrderValueTranslationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface
     */
    public function createMerchantRelationshipMinimumOrderValueGlossaryKeyGenerator(): MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface
    {
        return new MerchantRelationshipMinimumOrderValueGlossaryKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationReaderInterface
     */
    public function createMerchantRelationshipMinimumOrderValueTranslationReader(): MerchantRelationshipMinimumOrderValueTranslationReaderInterface
    {
        return new MerchantRelationshipMinimumOrderValueTranslationReader(
            $this->getGlossaryFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriterInterface
     */
    public function createMerchantRelationshipMinimumOrderValueTranslationWriter(): MerchantRelationshipMinimumOrderValueTranslationWriterInterface
    {
        return new MerchantRelationshipMinimumOrderValueTranslationWriter(
            $this->getGlossaryFacade()
        );
    }
}
