<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Assertion\Business\Model;

use Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException;

class Assertion implements AssertionInterface
{
    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertNumeric($value, $message = null)
    {
        if (!is_numeric($value)) {
            $this->throwException($message, 'Value is not numeric');
        }
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertNumericNotZero($value, $message = null)
    {
        $this->assertNumeric($value, $message);
        if ($value === 0) {
            $this->throwException('Value is zero', $message);
        }
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertAlphaNumeric($value, $message = null)
    {
        if (!ctype_alnum($value)) {
            $this->throwException('Value is not alpha numeric', $message);
        }
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertAlpha($value, $message = null)
    {
        if (!ctype_alpha($value)) {
            $this->throwException('Value is not alpha', $message);
        }
    }

    /**
     * @param mixed $value
     * @param string|null $message
     *
     * @return void
     */
    public function assertString($value, $message = null)
    {
        if (!is_string($value)) {
            $this->throwException('Value is not a string', $message);
        }
    }

    /**
     * @param string $message
     * @param string|null $userMessage
     *
     * @throws \Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException
     *
     * @return void
     */
    private function throwException($message, $userMessage = null)
    {
        if ($userMessage !== null) {
            $message = $userMessage;
        }

        throw new InvalidArgumentException($message);
    }
}
