<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Spryker\Zed\Discount\Business\Exception\QueryStringException;

class Tokenizer implements TokenizerInterface
{
    public const STRING_TO_TOKENS_REGEXP = '((\(|\)|["\'].*?["\'])|\s+)';

    /**
     * @param string $queryString
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return string[]
     */
    public function tokenizeQueryString($queryString)
    {
        $tokens = preg_split(
            self::STRING_TO_TOKENS_REGEXP,
            $queryString,
            null,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );

        $tokens = array_map('trim', $tokens);

        if (count($tokens) === 0) {
            throw new QueryStringException('Could not tokenize query string.');
        }

        return $tokens;
    }
}
