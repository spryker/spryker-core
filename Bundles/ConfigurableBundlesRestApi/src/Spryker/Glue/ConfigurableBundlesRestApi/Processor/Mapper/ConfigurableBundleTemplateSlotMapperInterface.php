<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer;

interface ConfigurableBundleTemplateSlotMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer $restConfigurableBundleTemplateSlotsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer
     */
    public function mapConfigurableBundleTemplateSlotStorageTransferToRestConfigurableBundleTemplateSlotsAttributesTransfer(
        ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer,
        RestConfigurableBundleTemplateSlotsAttributesTransfer $restConfigurableBundleTemplateSlotsAttributesTransfer
    ): RestConfigurableBundleTemplateSlotsAttributesTransfer;
}
