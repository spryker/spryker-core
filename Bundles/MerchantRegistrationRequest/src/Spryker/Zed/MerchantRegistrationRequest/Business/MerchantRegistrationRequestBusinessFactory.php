<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRegistrationRequest\Business\Acceptor\MerchantRegistrationRequestAcceptor;
use Spryker\Zed\MerchantRegistrationRequest\Business\Acceptor\MerchantRegistrationRequestAcceptorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantCreator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantCreatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantRegistrationRequestCreator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantRegistrationRequestCreatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantUserCreator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantUserCreatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Expander\MerchantExpander;
use Spryker\Zed\MerchantRegistrationRequest\Business\Expander\MerchantExpanderInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Expander\MerchantRegistrationRequestExpander;
use Spryker\Zed\MerchantRegistrationRequest\Business\Expander\MerchantRegistrationRequestExpanderInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Generator\MerchantReferenceGeneratorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Generator\UniqueRandomIdMerchantReferenceGenerator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Mapper\MerchantMapper;
use Spryker\Zed\MerchantRegistrationRequest\Business\Mapper\MerchantMapperInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Rejector\MerchantRegistrationRequestRejector;
use Spryker\Zed\MerchantRegistrationRequest\Business\Rejector\MerchantRegistrationRequestRejectorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Validator\CompanyNameMerchantRegistrationRequestValidator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Validator\EmailMerchantRegistrationRequestValidator;
use Spryker\Zed\MerchantRegistrationRequest\Business\Validator\MerchantRegistrationRequestValidatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCommentFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCountryFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToLocaleFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilTextServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig getConfig()
 */
class MerchantRegistrationRequestBusinessFactory extends AbstractBusinessFactory
{
    public function createMerchantRegistrationRequestCreator(): MerchantRegistrationRequestCreatorInterface
    {
        return new MerchantRegistrationRequestCreator(
            $this->getEntityManager(),
            $this->getCountryFacade(),
            $this->getConfig(),
            $this->getMerchantRegistrationRequestValidators(),
        );
    }

    public function createMerchantRegistrationRequestExpander(): MerchantRegistrationRequestExpanderInterface
    {
        return new MerchantRegistrationRequestExpander(
            $this->getCommentFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return list<\Spryker\Zed\MerchantRegistrationRequest\Business\Validator\MerchantRegistrationRequestValidatorInterface>
     */
    public function getMerchantRegistrationRequestValidators(): array
    {
        return [
            $this->createEmailMerchantRegistrationRequestValidator(),
            $this->createCompanyNameMerchantRegistrationRequestValidator(),
        ];
    }

    public function createEmailMerchantRegistrationRequestValidator(): MerchantRegistrationRequestValidatorInterface
    {
        return new EmailMerchantRegistrationRequestValidator($this->getRepository());
    }

    public function createCompanyNameMerchantRegistrationRequestValidator(): MerchantRegistrationRequestValidatorInterface
    {
        return new CompanyNameMerchantRegistrationRequestValidator($this->getRepository());
    }

    public function createMerchantRegistrationRequestAcceptor(): MerchantRegistrationRequestAcceptorInterface
    {
        return new MerchantRegistrationRequestAcceptor(
            $this->createMerchantCreator(),
            $this->createMerchantUserCreator(),
            $this->getEntityManager(),
            $this->getConfig(),
        );
    }

    public function createMerchantRegistrationRequestRejector(): MerchantRegistrationRequestRejectorInterface
    {
        return new MerchantRegistrationRequestRejector(
            $this->getEntityManager(),
            $this->getConfig(),
        );
    }

    public function createMerchantCreator(): MerchantCreatorInterface
    {
        return new MerchantCreator(
            $this->getMerchantFacade(),
            $this->createMerchantMapper(),
            $this->createMerchantExpander(),
            $this->createUniqueRandomIdMerchantReferenceGenerator(),
        );
    }

    public function createMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper(
            $this->getConfig(),
        );
    }

    public function createMerchantExpander(): MerchantExpanderInterface
    {
        return new MerchantExpander(
            $this->getLocaleFacade(),
            $this->getUtilTextService(),
            $this->getConfig(),
        );
    }

    public function createUniqueRandomIdMerchantReferenceGenerator(): MerchantReferenceGeneratorInterface
    {
        return new UniqueRandomIdMerchantReferenceGenerator(
            $this->getUtilUuidGeneratorService(),
            $this->getConfig(),
        );
    }

    public function createMerchantUserCreator(): MerchantUserCreatorInterface
    {
        return new MerchantUserCreator(
            $this->getMerchantUserFacade(),
        );
    }

    public function getCountryFacade(): MerchantRegistrationRequestToCountryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::FACADE_COUNTRY);
    }

    public function getMerchantFacade(): MerchantRegistrationRequestToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::FACADE_MERCHANT);
    }

    public function getMerchantUserFacade(): MerchantRegistrationRequestToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::FACADE_MERCHANT_USER);
    }

    public function getLocaleFacade(): MerchantRegistrationRequestToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::FACADE_LOCALE);
    }

    public function getUtilTextService(): MerchantRegistrationRequestToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::SERVICE_UTIL_TEXT);
    }

    public function getCommentFacade(): MerchantRegistrationRequestToCommentFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::FACADE_COMMENT);
    }

    public function getUtilUuidGeneratorService(): MerchantRegistrationRequestToUtilUuidGeneratorServiceInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::SERVICE_UTIL_UUID_GENERATOR);
    }
}
