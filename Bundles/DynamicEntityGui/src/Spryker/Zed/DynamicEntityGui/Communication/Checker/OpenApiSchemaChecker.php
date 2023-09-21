<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Checker;

use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;

class OpenApiSchemaChecker implements OpenApiSchemaCheckerInterface
{
    /**
     * @var string
     */
    protected const DATE_TIME_FROM_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig
     */
    protected DynamicEntityGuiConfig $config;

    /**
     * @var \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface
     */
    protected DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade;

    /**
     * @param \Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig $config
     * @param \Spryker\Zed\DynamicEntityGui\Dependency\Facade\DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade
     */
    public function __construct(
        DynamicEntityGuiConfig $config,
        DynamicEntityGuiToDynamicEntityFacadeInterface $dynamicEntityFacade
    ) {
        $this->config = $config;
        $this->dynamicEntityFacade = $dynamicEntityFacade;
    }

    /**
     * @return bool
     */
    public function isSchemaFileActual(): bool
    {
        $schemaFilepath = $this->config->getBackendApiSchemaPath();

        if (!file_exists($schemaFilepath)) {
            return false;
        }
        /** @var int $time */
        $time = filemtime($schemaFilepath);

        if ($this->hasUpdatedConfigurations($time) === true) {
            return false;
        }

        return true;
    }

    /**
     * @param int $time
     *
     * @return bool
     */
    protected function hasUpdatedConfigurations(int $time): bool
    {
        $dynamicEntityConfigurationCriteriaTransfer = (new DynamicEntityConfigurationCriteriaTransfer())->setDynamicEntityConfigurationConditions(
            (new DynamicEntityConfigurationConditionsTransfer())->setFilterUpdatedAt(
                (new CriteriaRangeFilterTransfer())->setFrom(date(static::DATE_TIME_FROM_FORMAT, $time)),
            ),
        );

        return $this->dynamicEntityFacade
                ->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer)
                ->getDynamicEntityConfigurations()
                ->count() > 0;
    }
}
