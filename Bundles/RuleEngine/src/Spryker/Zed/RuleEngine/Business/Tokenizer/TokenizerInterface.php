<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Tokenizer;

interface TokenizerInterface
{
    /**
     * @param string $queryString
     *
     * @throws \Spryker\Zed\RuleEngine\Business\Exception\QueryStringException
     *
     * @return list<string>
     */
    public function tokenizeQueryString(string $queryString): array;
}
