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
 *
 * @property \SprykerTest\Zed\SearchElasticsearch\SearchElasticsearchZedTester $tester
 */
class SourceIdentifierTest extends Unit
{
    /**
     * @dataProvider sourceIdentifierTranslationDataProvider
     *
     * @param string[] $supportedSourceIdentifiers
     * @param string|null $expectedIndexName
     * @param string $indexPrefix
     * @param string $sourceIdentifier
     *
     * @return void
     */
    public function testCanTranslateSourceIdentifierToValidIndexNameWithoutDefinedPrefix(
        array $supportedSourceIdentifiers,
        ?string $expectedIndexName,
        string $indexPrefix,
        string $sourceIdentifier
    ): void {
        $this->tester->mockConfigMethod('getSupportedSourceIdentifiers', $supportedSourceIdentifiers);
        $this->tester->mockConfigMethod('getIndexPrefix', $indexPrefix);

        // Arrange
        $sourceIdentifierModel = new SourceIdentifier($this->tester->getModuleConfig());

        // Act
        $resolvedIndexName = $sourceIdentifierModel->translateToIndexName($sourceIdentifier);

        // Assert
        $this->assertEquals($expectedIndexName, $resolvedIndexName);
    }

    /**
     * @return array
     */
    public function sourceIdentifierTranslationDataProvider(): array
    {
        return [
            'no store prefix one' => [
                ['foo', 'bar'],
                $this->buildIndexName('foo', '', $this->getCurrentStore()),
                '',
                'foo',
            ],
            'no store prefix two' => [
                ['foo', 'bar'],
                 $this->buildIndexName('foo', 'test', $this->getCurrentStore()),
                'test',
                'foo',
            ],
            'current store prefix one' => [
                ['foo', 'bar'],
                $this->buildIndexName('de_foo'),
                '',
                'de_foo',
            ],
            'current store prefix two' => [
                ['foo', 'bar'],
                $this->buildIndexName('de_foo', 'test'),
                'test',
                'de_foo',
            ],
        ];
    }

    /**
     * @param string $sourceIdentifier
     * @param string $indexPrefix
     * @param string|null $currentStore
     *
     * @return string
     */
    protected function buildIndexName(string $sourceIdentifier, string $indexPrefix = '', ?string $currentStore = null): string
    {
        $indexParameters = [
            $indexPrefix,
            $currentStore,
            $sourceIdentifier,
        ];

        return mb_strtolower(implode('_', array_filter($indexParameters)));
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return APPLICATION_STORE;
    }
}
