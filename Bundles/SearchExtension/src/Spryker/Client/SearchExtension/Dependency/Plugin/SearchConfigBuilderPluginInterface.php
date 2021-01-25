<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchConfigurationTransfer;

interface SearchConfigBuilderPluginInterface
{
    /**
     * Specification:
     * - Builds search configuration.
     * - Populates search configuration transfer with vendor specific search configuration.
     * - Returns populated search configuration transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    public function buildConfig(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationTransfer;
}
