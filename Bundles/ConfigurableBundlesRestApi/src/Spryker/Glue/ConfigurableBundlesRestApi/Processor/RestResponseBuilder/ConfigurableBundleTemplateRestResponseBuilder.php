<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapperInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class ConfigurableBundleTemplateRestResponseBuilder extends RestResponseBuilder implements ConfigurableBundleTemplateRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapperInterface
     */
    protected $configurableBundleTemplateMapper;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslatorInterface
     */
    protected $configurableBundleTempleTranslator;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleTemplateMapperInterface $configurableBundleTemplateMapper
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTempleTranslatorInterface $configurableBundleTempleTranslator
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ConfigurableBundleTemplateMapperInterface $configurableBundleTemplateMapper,
        ConfigurableBundleTempleTranslatorInterface $configurableBundleTempleTranslator
    ) {
        parent::__construct($restResourceBuilder);

        $this->configurableBundleTemplateMapper = $configurableBundleTemplateMapper;
        $this->configurableBundleTempleTranslator = $configurableBundleTempleTranslator;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildConfigurableBundleTemplateRestResponse(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        string $localeName
    ): RestResponseInterface {
        $configurableBundleTemplateStorageTransfers = $this->configurableBundleTempleTranslator
            ->translateConfigurableBundleTemplateNames([$configurableBundleTemplateStorageTransfer], $localeName);

        $restResource = $this->createConfigurableBundleTemplateRestResource(
            current($configurableBundleTemplateStorageTransfers)
        );

        return $this->createRestResponse()->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer[] $configurableBundleTemplateStorageTransfers
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildConfigurableBundleTemplateCollectionRestResponse(
        array $configurableBundleTemplateStorageTransfers,
        string $localeName
    ): RestResponseInterface {
        $configurableBundleTemplateStorageTransfers = $this->configurableBundleTempleTranslator
            ->translateConfigurableBundleTemplateNames($configurableBundleTemplateStorageTransfers, $localeName);

        $restResponse = $this->createRestResponse();

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $restResponse->addResource(
                $this->createConfigurableBundleTemplateRestResource($configurableBundleTemplateStorageTransfer)
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createConfigurableBundleTemplateRestResource(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): RestResourceInterface {
        $restConfigurableBundleTemplatesAttributesTransfer = $this->configurableBundleTemplateMapper
            ->mapConfigurableBundleTemplateStorageTransferToRestConfigurableBundleTemplatesAttributesTransfer(
                $configurableBundleTemplateStorageTransfer,
                new RestConfigurableBundleTemplatesAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATES,
            $configurableBundleTemplateStorageTransfer->getUuid(),
            $restConfigurableBundleTemplatesAttributesTransfer
        )->setPayload($configurableBundleTemplateStorageTransfer);
    }
}
