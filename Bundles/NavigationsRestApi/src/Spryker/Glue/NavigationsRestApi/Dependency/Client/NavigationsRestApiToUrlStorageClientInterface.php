<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Dependency\Client;

interface NavigationsRestApiToUrlStorageClientInterface
{
    /**
     * @param array<string> $urlCollection
     *
     * @return array<\Generated\Shared\Transfer\UrlStorageTransfer>
     */
    public function getUrlStorageTransferByUrls(array $urlCollection): array;
}
