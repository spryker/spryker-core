<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressByMerchantReferenceResourceRelationshipExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressByMerchantReferenceResourceRelationshipExpanderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantRelationshipOrderResourceExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantRelationshipOrderResourceExpanderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpander;
use Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapper;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReader;
use Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantReaderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilder;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslator;
use Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface;
use Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolver;
use Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolverInterface;

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
            $this->createMerchantTranslator(),
            $this->createMerchantRestResponseBuilder(),
            $this->getMerchantSearchClient()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Reader\MerchantAddressReaderInterface
     */
    public function createMerchantAddressReader(): MerchantAddressReaderInterface
    {
        return new MerchantAddressReader(
            $this->getMerchantStorageClient(),
            $this->createMerchantAddressRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantRelationshipOrderResourceExpanderInterface
     */
    public function createMerchantRelationshipOrderResourceExpander(): MerchantRelationshipOrderResourceExpanderInterface
    {
        return new MerchantRelationshipOrderResourceExpander($this->createMerchantReader());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantResourceRelationshipExpanderInterface
     */
    public function createMerchantResourceRelationshipExpander(): MerchantResourceRelationshipExpanderInterface
    {
        return new MerchantResourceRelationshipExpander($this->createMerchantReader());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Expander\MerchantAddressByMerchantReferenceResourceRelationshipExpanderInterface
     */
    public function createMerchantAddressByMerchantReferenceResourceRelationshipExpander(): MerchantAddressByMerchantReferenceResourceRelationshipExpanderInterface
    {
        return new MerchantAddressByMerchantReferenceResourceRelationshipExpander($this->createMerchantAddressReader());
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
     * @return \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilderInterface
     */
    public function createMerchantAddressRestResponseBuilder(): MerchantAddressRestResponseBuilderInterface
    {
        return new MerchantAddressRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createMerchantAddressMapper()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface
     */
    public function createMerchantTranslator(): MerchantTranslatorInterface
    {
        return new MerchantTranslator($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantMapperInterface
     */
    public function createMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper(
            $this->getRestMerchantAttributesMapperPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantAttributesMapperPluginInterface[]
     */
    public function getRestMerchantAttributesMapperPlugins(): array
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::PLUGINS_REST_MERCHANT_ATTRIBUTES_MAPPER);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapperInterface
     */
    public function createMerchantAddressMapper(): MerchantAddressMapperInterface
    {
        return new MerchantAddressMapper();
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Processor\UrlResolver\MerchantUrlResolverInterface
     */
    public function createMerchantUrlResolver(): MerchantUrlResolverInterface
    {
        return new MerchantUrlResolver($this->getMerchantStorageClient());
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantsRestApiToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantsRestApiToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }

    /**
     * @return \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): MerchantsRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantsRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
