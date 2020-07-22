<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateImageSetRestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfigurableBundleTemplateImageSetExpander implements ConfigurableBundleRestResourceExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateImageSetRestResourceBuilderInterface
     */
    protected $configurableBundleTemplateImageSetRestResourceBuilder;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateImageSetRestResourceBuilderInterface $configurableBundleTemplateImageSetRestResourceBuilder
     */
    public function __construct(
        ConfigurableBundleTemplateImageSetRestResourceBuilderInterface $configurableBundleTemplateImageSetRestResourceBuilder
    ) {
        $this->configurableBundleTemplateImageSetRestResourceBuilder = $configurableBundleTemplateImageSetRestResourceBuilder;
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

            foreach ($configurableBundleTemplateStorageTransfer->getImageSets() as $productImageSetStorageTransfer) {
                $configurableBundleTemplateSlotRestResource = $this->configurableBundleTemplateImageSetRestResourceBuilder
                    ->buildConfigurableBundleTemplateImageSetRestResource(
                        $productImageSetStorageTransfer,
                        $configurableBundleTemplateStorageTransfer->getUuid()
                    )
                    ->addLink(
                        RestLinkInterface::LINK_SELF,
                        $this->createConfigurableBundleTemplateImageSetSelfLink($resource)
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
    protected function createConfigurableBundleTemplateImageSetSelfLink(RestResourceInterface $parentRestResource): string
    {
        return sprintf(
            '%s/%s?include=%s',
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATES,
            $parentRestResource->getId(),
            ConfigurableBundlesRestApiConfig::RESOURCE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE_SETS
        );
    }
}
