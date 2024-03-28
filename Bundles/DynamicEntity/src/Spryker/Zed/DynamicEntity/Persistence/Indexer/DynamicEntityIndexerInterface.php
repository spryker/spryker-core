<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Indexer;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

interface DynamicEntityIndexerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string|array<mixed>> $indexedDynamicEntityConfigurations
     *
     * @return array<string, string|array<mixed>>
     */
    public function getChildDynamicEntityConfigurationsIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDynamicEntityConfigurations = []
    ): array;
}
