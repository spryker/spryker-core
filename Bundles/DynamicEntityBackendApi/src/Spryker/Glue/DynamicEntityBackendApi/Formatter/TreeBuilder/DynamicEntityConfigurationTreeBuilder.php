<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

class DynamicEntityConfigurationTreeBuilder implements DynamicEntityConfigurationTreeBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param int|null $deepLevel
     * @param string|null $parentRelationName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function buildDynamicEntityConfigurationTransferTree(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ?int $deepLevel = null,
        ?string $parentRelationName = null
    ): DynamicEntityConfigurationTransfer {
        if ($deepLevel === null) {
            return $dynamicEntityConfigurationTransfer;
        }

        if ($deepLevel === 0) {
            $dynamicEntityConfigurationTransfer->setChildRelations(new ArrayObject());

            return $dynamicEntityConfigurationTransfer;
        }

        /**
         * @var \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer $dynamicEntityConfigurationRelationTransfer
         */
        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $dynamicEntityConfigurationRelationTransfer) {
            if ($dynamicEntityConfigurationRelationTransfer->getNameOrFail() === $parentRelationName) {
                continue;
            }

            $dynamicEntityConfigurationRelationTransfer->setChildDynamicEntityConfiguration(
                $this->buildDynamicEntityConfigurationTransferTree(
                    $dynamicEntityConfigurationRelationTransfer->getChildDynamicEntityConfigurationOrFail(),
                    $deepLevel - 1,
                    $dynamicEntityConfigurationRelationTransfer->getNameOrFail(),
                ),
            );
        }

        return $dynamicEntityConfigurationTransfer;
    }
}
