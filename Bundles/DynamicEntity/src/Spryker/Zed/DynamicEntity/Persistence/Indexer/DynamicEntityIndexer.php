<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Indexer;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

class DynamicEntityIndexer implements DynamicEntityIndexerInterface
{
    /**
     * @var string
     */
    protected const KEY_CONFIGURATIONS = 'configurations';

    /**
     * @var string
     */
    protected const KEY_RELATION_FIELD_MAPPINGS = 'relationFieldMappings';

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param array<string, string|array<mixed>> $indexedDynamicEntityConfigurations
     *
     * @return array<string, string|array<mixed>>
     */
    public function getChildDynamicEntityConfigurationsIndexedByRelationName(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        array $indexedDynamicEntityConfigurations = []
    ): array {
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelation) {
            $childDynamicEntityConfigurationTransfer = $childRelation->getChildDynamicEntityConfigurationOrFail();

            if (isset($indexedDynamicEntityConfigurations[$childRelation->getNameOrFail()])) {
                continue;
            }

            $indexedDynamicEntityConfigurations[$childRelation->getNameOrFail()] = [
                static::KEY_CONFIGURATIONS => $childDynamicEntityConfigurationTransfer,
                static::KEY_RELATION_FIELD_MAPPINGS => $childRelation->getRelationFieldMappings(),
            ];

            if ($childDynamicEntityConfigurationTransfer->getChildRelations()->count() > 0) {
                $indexedDynamicEntityConfigurations = $this->getChildDynamicEntityConfigurationsIndexedByRelationName(
                    $childDynamicEntityConfigurationTransfer,
                    $indexedDynamicEntityConfigurations,
                );
            }
        }

        return $indexedDynamicEntityConfigurations;
    }
}
