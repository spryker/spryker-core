<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpander;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpanderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpander;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractor;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractor;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractor;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractor;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilter;
use Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilterInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Iterator\ProductOfferServiceIterator;
use Spryker\Zed\ProductOfferServicePoint\Business\Iterator\ProductOfferServiceIteratorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapper;
use Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReader;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReader;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Saver\ProductOfferServiceSaver;
use Spryker\Zed\ProductOfferServicePoint\Business\Saver\ProductOfferServiceSaverInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidator;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidatorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\HasSingleServicePointProductOfferValidatorRule;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ReferenceExistsProductOfferValidatorRule;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ServiceExistsProductOfferValidatorRule;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ServiceUniquenessProductOfferValidatorRule;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\UniquenessProductOfferValidatorRule;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdder;
use Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface getEntityManager()
 */
class ProductOfferServicePointBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferExpanderInterface
     */
    public function createProductOfferExpander(): ProductOfferExpanderInterface
    {
        return new ProductOfferExpander(
            $this->getRepository(),
            $this->createServiceReader(),
            $this->createProductOfferServiceExtractor(),
            $this->createProductOfferExtractor(),
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface
     */
    public function createProductOfferServiceExpander(): ProductOfferServiceExpanderInterface
    {
        return new ProductOfferServiceExpander(
            $this->createProductOfferServiceExtractor(),
            $this->createProductOfferReader(),
            $this->createServiceReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Saver\ProductOfferServiceSaverInterface
     */
    public function createProductOfferServiceSaver(): ProductOfferServiceSaverInterface
    {
        return new ProductOfferServiceSaver(
            $this->getEntityManager(),
            $this->createProductOfferValidator(),
            $this->createProductOfferFilter(),
            $this->getRepository(),
            $this->createProductOfferExpander(),
            $this->createProductOfferExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\ProductOfferValidatorInterface
     */
    public function createProductOfferValidator(): ProductOfferValidatorInterface
    {
        return new ProductOfferValidator(
            $this->getProductOfferValidatorRules(),
        );
    }

    /**
     * @return list<\Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface>
     */
    public function getProductOfferValidatorRules(): array
    {
        return [
            $this->createReferenceExistsProductOfferValidatorRule(),
            $this->createServiceExistsProductOfferValidatorRule(),
            $this->createHasSingleServicePointProductOfferValidatorRule(),
            $this->createServiceUniquenessProductOfferValidatorRule(),
            $this->createUniquenessProductOfferValidatorRule(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Filter\ProductOfferFilterInterface
     */
    public function createProductOfferFilter(): ProductOfferFilterInterface
    {
        return new ProductOfferFilter(
            $this->createErrorExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractorInterface
     */
    public function createErrorExtractor(): ErrorExtractorInterface
    {
        return new ErrorExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    public function createErrorAdder(): ErrorAdderInterface
    {
        return new ErrorAdder();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface
     */
    public function createHasSingleServicePointProductOfferValidatorRule(): ProductOfferValidatorRuleInterface
    {
        return new HasSingleServicePointProductOfferValidatorRule(
            $this->createErrorAdder(),
            $this->createProductOfferExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface
     */
    public function createServiceReader(): ServiceReaderInterface
    {
        return new ServiceReader(
            $this->getServicePointFacade(),
            $this->createProductOfferServiceMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface
     */
    public function createServiceUniquenessProductOfferValidatorRule(): ProductOfferValidatorRuleInterface
    {
        return new ServiceUniquenessProductOfferValidatorRule(
            $this->createErrorAdder(),
            $this->createServiceExtractor(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface
     */
    public function createReferenceExistsProductOfferValidatorRule(): ProductOfferValidatorRuleInterface
    {
        return new ReferenceExistsProductOfferValidatorRule(
            $this->createErrorAdder(),
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface
     */
    public function createProductOfferReader(): ProductOfferReaderInterface
    {
        return new ProductOfferReader(
            $this->getProductOfferFacade(),
            $this->createProductOfferServiceMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface
     */
    public function createProductOfferServiceMapper(): ProductOfferServiceMapperInterface
    {
        return new ProductOfferServiceMapper();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Iterator\ProductOfferServiceIteratorInterface
     */
    public function createProductOfferServiceIterator(): ProductOfferServiceIteratorInterface
    {
        return new ProductOfferServiceIterator(
            $this->getConfig(),
            $this->getRepository(),
            $this->createProductOfferServiceExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\ProductOfferValidatorRuleInterface
     */
    public function createServiceExistsProductOfferValidatorRule(): ProductOfferValidatorRuleInterface
    {
        return new ServiceExistsProductOfferValidatorRule(
            $this->createProductOfferExtractor(),
            $this->createServiceExtractor(),
            $this->createServiceReader(),
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Validator\Rule\ProductOffer\UniquenessProductOfferValidatorRule
     */
    public function createUniquenessProductOfferValidatorRule(): UniquenessProductOfferValidatorRule
    {
        return new UniquenessProductOfferValidatorRule(
            $this->createErrorAdder(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface
     */
    public function createProductOfferExtractor(): ProductOfferExtractorInterface
    {
        return new ProductOfferExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ServiceExtractorInterface
     */
    public function createServiceExtractor(): ServiceExtractorInterface
    {
        return new ServiceExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface
     */
    public function createProductOfferServiceExtractor(): ProductOfferServiceExtractorInterface
    {
        return new ProductOfferServiceExtractor();
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ProductOfferServicePointToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferServicePointToProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointDependencyProvider::FACADE_PRODUCT_OFFER);
    }
}
