<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

interface DefinitionInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array<string, mixed> $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition);
}
