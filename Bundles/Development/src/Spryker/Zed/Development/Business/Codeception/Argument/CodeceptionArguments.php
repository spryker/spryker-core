<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Codeception\Argument;

class CodeceptionArguments
{
    /**
     * @var string[]
     */
    protected $arguments = [];

    /**
     * @param string $argumentName
     * @param string[] $argumentValues
     *
     * @return $this
     */
    public function addArgument(string $argumentName, array $argumentValues = [])
    {
        if (empty($argumentValues)) {
            $this->arguments[] = $argumentName;

            return $this;
        }

        foreach ($argumentValues as $value) {
            $this->arguments[] = $argumentName;
            $this->arguments[] = $value;
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
