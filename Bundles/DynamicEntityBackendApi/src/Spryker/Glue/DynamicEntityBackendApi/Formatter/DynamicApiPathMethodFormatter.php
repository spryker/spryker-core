<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;

class DynamicApiPathMethodFormatter implements DynamicApiPathMethodFormatterInterface
{
    /**
     * @var array<\Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface>
     */
    protected array $builders;

    /**
     * @param array<\Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface> $builders
     */
    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     * @param array<mixed> $formattedData
     *
     * @return array<mixed>
     */
    public function format(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer, array $formattedData): array
    {
        foreach ($apiApplicationSchemaContextTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            $formattedData = $this->applyDynamicPathFormatters($formattedData, $dynamicEntityConfigurationTransfer);
        }

        return $formattedData;
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<mixed>
     */
    protected function applyDynamicPathFormatters(array $formattedData, DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        foreach ($this->builders as $builder) {
            $formattedData = array_merge_recursive($formattedData, $builder->buildPathData($dynamicEntityConfigurationTransfer));
        }

        return $formattedData;
    }
}
