<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\KeyBuilder;

use Codeception\Test\Unit;
use SprykerTest\Shared\KeyBuilder\Fixtures\KeyBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group KeyBuilder
 * @group KeyBuilderTraitTest
 * Add your own group annotations below this line
 */
class KeyBuilderTraitTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @dataProvider generateKey
     *
     * @param mixed $data
     * @param string $expectedGeneratedKey
     *
     * @return void
     */
    public function testGenerateKeyBuildExpectedStrings($data, string $expectedGeneratedKey): void
    {
        $keyBuilder = new KeyBuilder();
        $generatedKey = $keyBuilder->generateKey($data, 'de_DE', static::STORE_NAME);

        $this->assertSame($expectedGeneratedKey, $generatedKey);
    }

    /**
     * @return array
     */
    public function generateKey(): array
    {
        $storeName = strtolower(static::STORE_NAME);

        return [
            ['string', $storeName . '.de_de.key-builder.identifier.string'],
            [100, $storeName . '.de_de.key-builder.identifier.100'],
            [0.1, $storeName . '.de_de.key-builder.identifier.0.1'],
            ['foo' . "\n" . 'bar', $storeName . '.de_de.key-builder.identifier.foo-bar'],
            ['foo' . "\r" . 'bar', $storeName . '.de_de.key-builder.identifier.foo-bar'],
            ['foo "23" bar', $storeName . '.de_de.key-builder.identifier.foo--23--bar'],
            ['foo \'23\' bar', $storeName . '.de_de.key-builder.identifier.foo--23--bar'],
        ];
    }
}
