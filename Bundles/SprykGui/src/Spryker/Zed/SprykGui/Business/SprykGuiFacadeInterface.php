<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

interface SprykGuiFacadeInterface
{
    /**
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array;

    /**
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array;

    /**
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return bool
     */
    public function runSpryk(string $sprykName, array $sprykArguments): bool;
}
