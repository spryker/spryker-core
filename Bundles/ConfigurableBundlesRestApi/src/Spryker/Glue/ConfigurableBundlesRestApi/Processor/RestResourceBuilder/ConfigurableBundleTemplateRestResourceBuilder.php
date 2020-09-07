<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ConfigurableBundleTemplateRestResourceBuilder implements ConfigurableBundleTemplateRestResourceBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleMapperInterface
     */
    protected $configurableBundleMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleMapperInterface $configurableBundleMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ConfigurableBundleMapperInterface $configurableBundleMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->configurableBundleMapper = $configurableBundleMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function buildConfigurableBundleTemplateRestResource(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
    ): RestResourceInterface {
        $restConfigurableBundleTemplatesAttributesTransfer = $this->configurableBundleMapper
            ->mapConfigurableBundleTemplateStorageTransferToRestAttributesTransfer(
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
