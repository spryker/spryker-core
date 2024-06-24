<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\Tokenizer;

use Codeception\Test\Unit;
use Spryker\Zed\RuleEngine\Business\Tokenizer\Tokenizer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RuleEngine
 * @group Business
 * @group Tokenizer
 * @group TokenizerTest
 * Add your own group annotations below this line
 */
class TokenizerTest extends Unit
{
    /**
     * @dataProvider returnsCorrectNumberOfTokensDataProvider
     *
     * @param string $queryString
     * @param int $expectedNumberOfTokens
     *
     * @return void
     */
    public function testReturnsCorrectNumberOfTokens(string $queryString, int $expectedNumberOfTokens): void
    {
        // Act
        $tokens = $this->createTokenizer()->tokenizeQueryString($queryString);

        // Assert
        $this->assertCount($expectedNumberOfTokens, $tokens);
    }

    /**
     * @return array<string, list<string|int>>
     */
    protected function returnsCorrectNumberOfTokensDataProvider(): array
    {
        return [
            'Should return each word as a token when spaces used' => ['one two     and three  ', 4],
            'Should treat as a single token what is inside quotes' => ['sku = "one two three" ', 3],
            'Should be used as separate token when parenthesis is used' => [' ( one and two) ', 5],
        ];
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Tokenizer\Tokenizer
     */
    protected function createTokenizer(): Tokenizer
    {
        return new Tokenizer();
    }
}
