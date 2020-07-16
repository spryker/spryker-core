<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi;

use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\ConfigurableBundleTemplate\ConfigurableBundleTemplateReader;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\ConfigurableBundleTemplate\ConfigurableBundleTemplateReaderInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander\ConfigurableBundleSlotExpander;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander\ConfigurableBundleSlotExpanderInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapper;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapperInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilder;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslator;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ConfigurableBundlesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Processor\ConfigurableBundleTemplate\ConfigurableBundleTemplateReaderInterface
     */
    public function createConfigurableBundleTemplateReader(): ConfigurableBundleTemplateReaderInterface
    {
        return new ConfigurableBundleTemplateReader(
            $this->getConfigurableBundleStorageClient(),
            $this->getConfigurableBundlePageSearchClient(),
            $this->createConfigurableBundleTemplateRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface
     */
    public function createConfigurableBundleTemplateRestResponseBuilder(): ConfigurableBundleTemplateRestResponseBuilderInterface
    {
        return new ConfigurableBundleTemplateRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createConfigurableBundleTemplateMapper(),
            $this->createConfigurableBundleTempleTranslator()
        );
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapperInterface
     */
    public function createConfigurableBundleTemplateMapper(): ConfigurableBundleTemplateMapperInterface
    {
        return new ConfigurableBundleTemplateMapper();
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslatorInterface
     */
    public function createConfigurableBundleTempleTranslator(): ConfigurableBundleTempleTranslatorInterface
    {
        return new ConfigurableBundleTempleTranslator($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander\ConfigurableBundleSlotExpanderInterface
     */
    public function createConfigurableBundleSlotExpander(): ConfigurableBundleSlotExpanderInterface
    {
        return new ConfigurableBundleSlotExpander();
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface
     */
    public function getConfigurableBundleStorageClient(): ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlesRestApiDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface
     */
    public function getConfigurableBundlePageSearchClient(): ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlesRestApiDependencyProvider::CLIENT_CONFIGURABLE_BUNDLE_PAGE_SEARCH);
    }

    /**
     * @return \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ConfigurableBundlesRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlesRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
