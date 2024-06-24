<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Tokenizer;

use Spryker\Zed\RuleEngine\Business\Exception\QueryStringException;

class Tokenizer implements TokenizerInterface
{
    /**
     * @var string
     */
    protected const STRING_TO_TOKENS_REGEXP = '((\(|\)|["\'].*?["\'])|\s+)';

    /**
     * @param string $queryString
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return list<string>
     */
    public function tokenizeQueryString(string $queryString): array
    {
        /** @var list<string> $tokens */
        $tokens = preg_split(
            static::STRING_TO_TOKENS_REGEXP,
            $queryString,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE,
        );

        if ($tokens === []) {
            throw new QueryStringException('Could not tokenize query string.');
        }

        return array_map('trim', $tokens);
    }
}
