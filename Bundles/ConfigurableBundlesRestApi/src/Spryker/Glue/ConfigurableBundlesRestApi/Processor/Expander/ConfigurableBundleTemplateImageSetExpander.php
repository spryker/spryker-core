<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateImageSetRestResourceBuilderInterface;
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
                    );

                $resource->addRelationship($configurableBundleTemplateSlotRestResource);
            }
        }
    }
}
