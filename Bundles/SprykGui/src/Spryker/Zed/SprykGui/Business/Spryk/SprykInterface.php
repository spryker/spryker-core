<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Spryk;

interface SprykInterface
{
    /**
     * @return array
     */
    public function getSprykDefinitions(): array;

    /**
     * @param string $spryk
     *
     * @return array
     */
    public function getSprykDefinitionByName(string $spryk): array;

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return mixed
     */
    public function buildSprykView(string $sprykName, array $sprykArguments);

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string;

    /**
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string;
}
