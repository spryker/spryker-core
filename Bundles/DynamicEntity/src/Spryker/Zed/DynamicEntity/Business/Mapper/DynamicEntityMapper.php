<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Mapper;

use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldDefinitionTransfer;

class DynamicEntityMapper
{
    /**
     * @var string
     */
    protected const FIELDS = 'fields';

    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const VALIDATION = 'validation';

    /**
     * @var string
     */
    protected const DEFINITION = 'definition';

    /**
     * @param array<string, mixed> $dynamicEntityConfiguration
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    public function mapDynamicEntityConfigurationToDynamicEntityConfigurationTransfer(
        array $dynamicEntityConfiguration,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityConfigurationTransfer {
        $dynamicEntityConfigurationTransfer->fromArray($dynamicEntityConfiguration, true);

        $dynamicEntityConfigurationTransfer->setDynamicEntityDefinition(
            $this->mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
                $dynamicEntityConfiguration[static::DEFINITION],
                new DynamicEntityDefinitionTransfer(),
            ),
        );

        return $dynamicEntityConfigurationTransfer;
    }

    /**
     * @param array<string, mixed> $definition
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer
     */
    protected function mapDynamicEntityDefinitionToDynamicEntityDefinitionTransfer(
        array $definition,
        DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
    ): DynamicEntityDefinitionTransfer {
        if (!isset($definition[static::FIELDS])) {
            return $dynamicEntityDefinitionTransfer;
        }

        $dynamicEntityDefinitionTransfer->setIdentifier($definition[static::IDENTIFIER]);

        foreach ($definition[static::FIELDS] as $field) {
            $dynamicEntityFieldDefinitionTransfer = (new DynamicEntityFieldDefinitionTransfer())->fromArray($field, true);

            $dynamicEntityDefinitionTransfer->addFieldDefinition(
                $dynamicEntityFieldDefinitionTransfer,
            );
        }

        return $dynamicEntityDefinitionTransfer;
    }
}
