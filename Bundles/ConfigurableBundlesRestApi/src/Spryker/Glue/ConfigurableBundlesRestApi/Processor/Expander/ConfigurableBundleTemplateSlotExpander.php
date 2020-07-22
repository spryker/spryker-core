<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfigurableBundleTemplateSlotExpander implements ConfigurableBundleRestResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface
     */
    protected $configurableBundleSlotRestResourceBuilder;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface $configurableBundleSlotRestResourceBuilder
     */
    public function __construct(
        ConfigurableBundleTemplateSlotRestResourceBuilderInterface $configurableBundleSlotRestResourceBuilder
    ) {
        $this->configurableBundleSlotRestResourceBuilder = $configurableBundleSlotRestResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $configurableBundleTemplateStorageTransfer = $resource->getPayload();
            if (!$configurableBundleTemplateStorageTransfer instanceof ConfigurableBundleTemplateStorageTransfer) {
                continue;
            }

            foreach ($configurableBundleTemplateStorageTransfer->getSlots() as $configurableBundleTemplateSlotStorageTransfer) {
                $configurableBundleTemplateSlotRestResource = $this->configurableBundleSlotRestResourceBuilder
                    ->buildConfigurableBundleTemplateSlotRestResource($configurableBundleTemplateSlotStorageTransfer)
                    ->addLink(
                        RestLinkInterface::LINK_SELF,
                        $this->createConfigurableBundleTemplateSlotSelfLink($resource)
                    );

                $resource->addRelationship($configurableBundleTemplateSlotRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $parentRestResource
     *
     * @return string
     */
    protected function createConfigurableBundleTemplateSlotSelfLink(RestResourceInterface $parentRestResource): string
    {
        return sprintf(
            '%s/%s?include=%s',
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATES,
            $parentRestResource->getId(),
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOTS
        );
    }
}
