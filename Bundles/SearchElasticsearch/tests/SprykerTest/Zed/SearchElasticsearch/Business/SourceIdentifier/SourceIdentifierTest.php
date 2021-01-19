<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchElasticsearch\Business\SourceIdentifier;

use Codeception\Test\Unit;
use Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifier;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group SourceIdentifier
 * @group SourceIdentifierTest
 * Add your own group annotations below this line
 */
class SourceIdentifierTest extends Unit
{
    /**
     * @dataProvider canTranslateSourceIdentifierToValidIndexNameDataProvider
     *
     * @param string|null $expectedIndexName
     * @param string $sourceIdentifier
     * @param array $supportedSourceIdentifiers
     *
     * @return void
     */
    public function testCanTranslateSourceIdentifierToValidIndexName(
        ?string $expectedIndexName,
        string $sourceIdentifier,
        array $supportedSourceIdentifiers
    ): void {
        // Arrange
        $sourceIdentifierModel = new SourceIdentifier($supportedSourceIdentifiers);

        // Act
        $resolvedIndexName = $sourceIdentifierModel->translateToIndexName($sourceIdentifier);

        // Assert
        $this->assertEquals($expectedIndexName, $resolvedIndexName);
    }

    /**
     * @return array
     */
    public function canTranslateSourceIdentifierToValidIndexNameDataProvider(): array
    {
        return [
            'no store prefix' => [
                $this->buildIndexName('foo'),
                'foo',
                ['foo', 'bar'],
            ],
            'current store prefix' => [
                $this->buildIndexName('foo'),
                'de_foo',
                ['foo', 'bar'],
            ],
        ];
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return string
     */
    protected function buildIndexName(string $sourceIdentifier): string
    {
        return mb_strtolower(
            sprintf('%s_%s', APPLICATION_STORE, $sourceIdentifier)
        );
    }
}
