<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateRestResourceBuilderInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleRestApiTranslatorInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ConfigurableBundleTemplateRestResponseBuilder implements ConfigurableBundleTemplateRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateRestResourceBuilderInterface
     */
    protected $configurableBundleTemplateRestResourceBuilder;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleRestApiTranslatorInterface
     */
    protected $configurableBundleRestApiTranslator;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateRestResourceBuilderInterface $configurableBundleTemplateRestResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleRestApiTranslatorInterface $configurableBundleRestApiTranslator
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ConfigurableBundleTemplateRestResourceBuilderInterface $configurableBundleTemplateRestResourceBuilder,
        ConfigurableBundleRestApiTranslatorInterface $configurableBundleRestApiTranslator
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->configurableBundleTemplateRestResourceBuilder = $configurableBundleTemplateRestResourceBuilder;
        $this->configurableBundleRestApiTranslator = $configurableBundleRestApiTranslator;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildConfigurableBundleTemplateNotFoundErrorRestResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(ConfigurableBundlesRestApiConfig::RESPONSE_CODE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ConfigurableBundlesRestApiConfig::RESPONSE_DETAIL_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

        return $this->createRestResponse()->addError($restErrorMessageTransfer);
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
        $configurableBundleTemplateStorageTransfers = $this->configurableBundleRestApiTranslator
            ->translateConfigurableBundleTemplateStorageTransfers(
                [$configurableBundleTemplateStorageTransfer],
                $localeName
            );

        $restResource = $this->configurableBundleTemplateRestResourceBuilder
            ->buildConfigurableBundleTemplateRestResource(current($configurableBundleTemplateStorageTransfers));

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
        $configurableBundleTemplateStorageTransfers = $this->configurableBundleRestApiTranslator
            ->translateConfigurableBundleTemplateStorageTransfers(
                $configurableBundleTemplateStorageTransfers,
                $localeName
            );

        $restResponse = $this->createRestResponse();

        foreach ($configurableBundleTemplateStorageTransfers as $configurableBundleTemplateStorageTransfer) {
            $restResponse->addResource(
                $this->configurableBundleTemplateRestResourceBuilder
                    ->buildConfigurableBundleTemplateRestResource($configurableBundleTemplateStorageTransfer)
            );
        }

        return $restResponse;
    }
}
