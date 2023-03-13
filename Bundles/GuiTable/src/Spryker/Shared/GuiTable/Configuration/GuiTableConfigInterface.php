<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Configuration;

interface GuiTableConfigInterface
{
    /**
     * @return string
     */
    public function getDefaultDataSourceType(): string;

    /**
     * @return array<string>
     */
    public function getDefaultEnabledFeatures(): array;

    /**
     * @return array<int>
     */
    public function getDefaultAvailablePageSizes(): array;

    /**
     * @return int
     */
    public function getDefaultPageSize(): int;

    /**
     * @return string
     */
    public function getDefaultSearchPlaceholder(): string;

    /**
     * @return string|null
     */
    public function getDefaultTimezone(): ?string;
}
