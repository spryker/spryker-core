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
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class ConfigurableBundleTemplateRestResponseBuilder implements ConfigurableBundleTemplateRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

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
        $this->restResourceBuilder = $restResourceBuilder;
        $this->configurableBundleTemplateMapper = $configurableBundleTemplateMapper;
        $this->configurableBundleTempleTranslator = $configurableBundleTempleTranslator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer[] $restErrorMessageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponse(array $restErrorMessageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($restErrorMessageTransfers as $restErrorMessageTransfer) {
            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
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
        $restConfigurableBundleTemplatesAttributesTransfer = $this->configurableBundleTemplateMapper
            ->mapConfigurableBundleTemplateStorageTransferToRestConfigurableBundleTemplatesAttributesTransfer(
                $configurableBundleTemplateStorageTransfer,
                new RestConfigurableBundleTemplatesAttributesTransfer()
            );

        return $this->createConfigurableBundleTemplateRestResponse(
            [$configurableBundleTemplateStorageTransfer->getUuid() => $restConfigurableBundleTemplatesAttributesTransfer],
            $localeName
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer[] $configurableBundleTemplatePageSearchTransfers
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildConfigurableBundleTemplateCollectionRestResponse(
        array $configurableBundleTemplatePageSearchTransfers,
        string $localeName
    ): RestResponseInterface {
        if (!$configurableBundleTemplatePageSearchTransfers) {
            return $this->restResourceBuilder->createRestResponse();
        }

        $restConfigurableBundleTemplatesAttributesTransfers = [];
        foreach ($configurableBundleTemplatePageSearchTransfers as $configurableBundleTemplatePageSearchTransfer) {
            $restConfigurableBundleTemplatesAttributesTransfers[$configurableBundleTemplatePageSearchTransfer->getUuid()] = $this->configurableBundleTemplateMapper
                ->mapConfigurableBundleTemplatePageSearchTransferToRestConfigurableBundleTemplatesAttributesTransfer(
                    $configurableBundleTemplatePageSearchTransfer,
                    new RestConfigurableBundleTemplatesAttributesTransfer()
                );
        }

        return $this->createConfigurableBundleTemplateRestResponse(
            $restConfigurableBundleTemplatesAttributesTransfers,
            $localeName
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer[] $restConfigurableBundleTemplatesAttributesTransfers
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createConfigurableBundleTemplateRestResponse(
        array $restConfigurableBundleTemplatesAttributesTransfers,
        string $localeName
    ): RestResponseInterface {
        $restConfigurableBundleTemplatesAttributesTransfers = $this->configurableBundleTempleTranslator
            ->translateConfigurableBundleTemplateNames($restConfigurableBundleTemplatesAttributesTransfers, $localeName);

        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($restConfigurableBundleTemplatesAttributesTransfers as $uuid => $restConfigurableBundleTemplatesAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATES,
                    $uuid,
                    $restConfigurableBundleTemplatesAttributesTransfer
                )
            );
        }

        return $restResponse;
    }
}
