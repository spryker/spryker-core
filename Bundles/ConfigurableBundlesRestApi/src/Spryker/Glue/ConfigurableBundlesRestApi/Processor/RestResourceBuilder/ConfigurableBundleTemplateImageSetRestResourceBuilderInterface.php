<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResourceBuilder;

use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface ConfigurableBundleTemplateImageSetRestResourceBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer $productImageSetStorageTransfer
     * @param string $idParentResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function buildConfigurableBundleTemplateImageSetRestResource(
        ProductImageSetStorageTransfer $productImageSetStorageTransfer,
        string $idParentResource
    ): RestResourceInterface;
}
