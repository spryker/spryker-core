<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Model;

interface SprykInterface
{
    /**
     * @return array
     */
    public function getSprykDefinitions(): array;

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
     * @return mixed
     */
    public function runSpryk(string $sprykName, array $sprykArguments);

    /**
     * @param $sprykName
     * @return string
     */
    public function drawSpryk($sprykName): string;
}
