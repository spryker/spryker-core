<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressesByMerchantReferenceResourceRelationshipExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressesByMerchantReferenceResourceRelationshipExpanderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressesReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressesReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig getConfig()
 */
class MerchantsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantStorageClient(),
            $this->getGlossaryStorageClient(),
            $this->createMerchantRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressesReaderInterface
     */
    public function createMerchantAddressesReader(): MerchantAddressesReaderInterface
    {
        return new MerchantAddressesReader(
            $this->getMerchantStorageClient(),
            $this->createMerchantAddressesRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    public function createMerchantRestResponseBuilder(): MerchantRestResponseBuilderInterface
    {
        return new MerchantRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressesByMerchantReferenceResourceRelationshipExpanderInterface
     */
    public function createMerchantAddressesByMerchantReferenceResourceRelationshipExpander(): MerchantAddressesByMerchantReferenceResourceRelationshipExpanderInterface
    {
        return new MerchantAddressesByMerchantReferenceResourceRelationshipExpander(
            $this->getMerchantStorageClient(),
            $this->createMerchantAddressesRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface
     */
    public function createMerchantAddressesRestResponseBuilder(): MerchantAddressesRestResponseBuilderInterface
    {
        return new MerchantAddressesRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantAddressesMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantsStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface
     */
    public function createMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper();
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapperInterface
     */
    public function createMerchantAddressesMapper(): MerchantAddressesMapperInterface
    {
        return new MerchantAddressesMapper();
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): MerchantsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
