<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface ResourceIdentifierProviderPluginInterface
{
    /**
     * Specification:
     * - Takes UrlStorageTransfer and detects if it can provide the resource type and id for it.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return bool
     */
    public function isApplicable(UrlStorageTransfer $urlStorageTransfer): bool;

    /**
     * Specification:
     * - Attempts to find the type+id pair for the the given UrlStorageTransfer.
     * - Returns ResourceIdentifierTransfer on success, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer|null
     */
    public function provideResourceIdentifierByUrlStorageTransfer(
        UrlStorageTransfer $urlStorageTransfer
    ): ?ResourceIdentifierTransfer;
}
