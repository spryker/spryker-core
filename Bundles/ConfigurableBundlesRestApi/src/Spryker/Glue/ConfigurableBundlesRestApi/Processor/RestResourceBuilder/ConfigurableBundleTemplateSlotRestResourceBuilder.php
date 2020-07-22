<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleRestApiMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ConfigurableBundleTemplateSlotRestResourceBuilder implements ConfigurableBundleTemplateSlotRestResourceBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleRestApiMapperInterface
     */
    protected $configurableBundleRestApiMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper\ConfigurableBundleRestApiMapperInterface $configurableBundleRestApiMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ConfigurableBundleRestApiMapperInterface $configurableBundleRestApiMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->configurableBundleRestApiMapper = $configurableBundleRestApiMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function buildConfigurableBundleTemplateSlotRestResource(
        ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
    ): RestResourceInterface {
        $restConfigurableBundleTemplateSlotsAttributesTransfer = $this->configurableBundleRestApiMapper
            ->mapConfigurableBundleTemplateSlotStorageTransferToRestAttributesTransfer(
                $configurableBundleTemplateSlotStorageTransfer,
                new RestConfigurableBundleTemplateSlotsAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS,
            $configurableBundleTemplateSlotStorageTransfer->getUuid(),
            $restConfigurableBundleTemplateSlotsAttributesTransfer
        )->setPayload($configurableBundleTemplateSlotStorageTransfer);
    }
}
