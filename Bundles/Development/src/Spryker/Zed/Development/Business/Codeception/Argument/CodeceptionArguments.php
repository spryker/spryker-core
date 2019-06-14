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
     * @param array $argumentValue
     *
     * @return $this
     */
    public function addArgument(string $argumentName, array $argumentValue = [])
    {
        if (empty($argumentValue)) {
            $this->arguments[] = $argumentName;

            return $this;
        }

        foreach ($argumentValue as $value) {
            $this->arguments[] = $argumentName . ' ' . $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    public function asString(): string
    {
        if (count($this->arguments) === 0) {
            return '';
        }

        return ' ' . implode(' ', $this->arguments);
    }
}
