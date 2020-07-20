<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTemplateSlotTranslatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfigurableBundleTemplateSlotExpander implements ConfigurableBundleTemplateSlotExpanderInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface
     */
    protected $configurableBundleSlotRestResourceBuilder;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTemplateSlotTranslatorInterface
     */
    protected $configurableBundleTemplateSlotTranslator;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder\ConfigurableBundleTemplateSlotRestResourceBuilderInterface $configurableBundleSlotRestResourceBuilder
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator\ConfigurableBundleTemplateSlotTranslatorInterface $configurableBundleTemplateSlotTranslator
     */
    public function __construct(
        ConfigurableBundleTemplateSlotRestResourceBuilderInterface $configurableBundleSlotRestResourceBuilder,
        ConfigurableBundleTemplateSlotTranslatorInterface $configurableBundleTemplateSlotTranslator
    ) {
        $this->configurableBundleSlotRestResourceBuilder = $configurableBundleSlotRestResourceBuilder;
        $this->configurableBundleTemplateSlotTranslator = $configurableBundleTemplateSlotTranslator;
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

            $configurableBundleTemplateSlotStorageTransfers = $this->configurableBundleTemplateSlotTranslator
                ->translateConfigurableBundleTemplateSlotNames(
                    $configurableBundleTemplateStorageTransfer->getSlots()->getArrayCopy(),
                    $restRequest->getMetadata()->getLocale()
                );

            foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
                $configurableBundleTemplateSlotRestResource = $this->configurableBundleSlotRestResourceBuilder
                    ->buildConfigurableBundleTemplateSlotRestResource($configurableBundleTemplateSlotStorageTransfer);

                $resource->addRelationship($configurableBundleTemplateSlotRestResource);
            }
        }
    }
}
