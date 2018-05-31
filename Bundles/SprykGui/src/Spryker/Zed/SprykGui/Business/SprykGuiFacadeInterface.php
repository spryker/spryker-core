<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

interface SprykGuiFacadeInterface
{
    /**
     * Specification:
     * - Builds the template for JIRA.
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array;

    /**
     * Specification:
     * - Returns all SprykDefinitions.
     *
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array;

    /**
     * Specification:
     * - Renders a rph for the given Spryk name.
     *
     * @api
     *
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string;

    /**
     * Specification:
     * - Builds the commandLin to be executed and executes it.
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string;
}
