<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Plugin\Catalog\ResultFormatter;

use Codeception\Test\Unit;
use Spryker\Client\SearchHttp\Plugin\Catalog\ResultFormatter\SpellingSuggestionSearchHttpResultFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Plugin
 * @group Catalog
 * @group ResultFormatter
 * @group SpellingSuggestionSearchHttpResultFormatterPluginTest
 * Add your own group annotations below this line
 */
class SpellingSuggestionSearchHttpResultFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductsResultFormatterHasProperName(): void
    {
        // Arrange
        $spellingSuggestionSearchHttpResultFormatterPlugin = new SpellingSuggestionSearchHttpResultFormatterPlugin();

        // Act
        $name = $spellingSuggestionSearchHttpResultFormatterPlugin->getName();

        // Assert
        $this->assertEquals('spellingSuggestion', $name);
    }
}
