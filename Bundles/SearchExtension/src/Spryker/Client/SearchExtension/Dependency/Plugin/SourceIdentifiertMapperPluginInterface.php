<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SourceIdentifiertMapperPluginInterface
{
    /**
     * Specification:
     * - Maps source identifier to vendor specific source name.
     * - Mapped source name is set against the corresponding vendor specific transfer inside SearchContextTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function mapSourceIdentifier(SearchContextTransfer $searchContextTransfer): SearchContextTransfer;
}
