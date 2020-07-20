<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer;

interface ConfigurableBundleTemplateMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer $restConfigurableBundleTemplatesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer
     */
    public function mapConfigurableBundleTemplateStorageTransferToRestConfigurableBundleTemplatesAttributesTransfer(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        RestConfigurableBundleTemplatesAttributesTransfer $restConfigurableBundleTemplatesAttributesTransfer
    ): RestConfigurableBundleTemplatesAttributesTransfer;
}
