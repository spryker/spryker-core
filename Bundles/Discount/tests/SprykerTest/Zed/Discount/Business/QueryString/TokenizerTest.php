<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group TokenizerTest
 * Add your own group annotations below this line
 */
class TokenizerTest extends Unit
{
    /**
     * @return void
     */
    public function testWhenSpaceUsedShouldReturnEachWordAsAToken()
    {
        $tokenizer = $this->createTokenizer();
        $tokens = $tokenizer->tokenizeQueryString('one two     and three  ');
        $this->assertCount(4, $tokens);
    }

    /**
     * @return void
     */
    public function testWhenQuotesUsedShouldThreadAsASingleTokenWhatIsInside()
    {
        $tokenizer = $this->createTokenizer();
        $tokens = $tokenizer->tokenizeQueryString('sku = "one two three" ');

        $this->assertCount(3, $tokens);
    }

    /**
     * @return void
     */
    public function testWhenParenthesisIsUsedShouldBeUsedAsSeparateToken()
    {
        $tokenizer = $this->createTokenizer();
        $tokens = $tokenizer->tokenizeQueryString(' ( one and two) ');

        $this->assertCount(5, $tokens);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }
}
