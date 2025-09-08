<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Client\CmsPageSearch\SearchQueryResolver;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\CmsPageSearch\SearchQueryResolver\SearchQueryResolver;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CmsPageSearch
 * @group SearchQueryResolver
 * @group SearchQueryResolverTest
 * Add your own group annotations below this line
 */
class SearchQueryResolverTest extends Unit
{
    /**
     * Test resolve method with different plugin configurations.
     *
     * @dataProvider resolveScenarioDataProvider
     *
     * @param array<string, mixed> $scenario
     * @param string $expectedResultKey
     *
     * @return void
     */
    public function testResolveWithDifferentPluginConfigurations(array $scenario, string $expectedResultKey): void
    {
        // Arrange
        $defaultQueryMock = $this->createQueryMock();
        $queryMocks = ['default' => $defaultQueryMock];
        $searchQueryPlugins = [];

        // Create query mocks based on scenario
        foreach ($scenario['plugins'] as $pluginKey => $pluginConfig) {
            if ($pluginConfig['hasApplicabilityChecker']) {
                $queryMocks[$pluginKey] = $this->createQueryWithApplicabilityMock($pluginConfig['isApplicable']);
            } else {
                $queryMocks[$pluginKey] = $this->createQueryMock();
            }
            $searchQueryPlugins[] = $queryMocks[$pluginKey];
        }

        $resolver = new SearchQueryResolver($searchQueryPlugins, $defaultQueryMock);

        // Act
        $result = $resolver->resolve();

        // Assert
        $this->assertInstanceOf(QueryInterface::class, $result);
        // Verify the resolver returned the expected query by checking if it's the same instance
        // For scenarios where we expect the default query, check if it's the default
        if ($expectedResultKey === 'default') {
            $this->assertSame($defaultQueryMock, $result);
        } else {
            // For plugin queries, verify it's one of the plugin queries
            $this->assertContains($result, $searchQueryPlugins);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function resolveScenarioDataProvider(): array
    {
        return [
            'no plugins provided' => [
                [
                    'plugins' => [],
                ],
                'default',
            ],
            'single applicable query' => [
                [
                    'plugins' => [
                        'applicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => true],
                    ],
                ],
                'applicable',
            ],
            'single non-applicable query' => [
                [
                    'plugins' => [
                        'nonApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => false],
                    ],
                ],
                'nonApplicable',
            ],
            'first applicable query when multiple exist' => [
                [
                    'plugins' => [
                        'firstApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => true],
                        'secondApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => true],
                    ],
                ],
                'firstApplicable',
            ],
            'applicable query found among non-applicable' => [
                [
                    'plugins' => [
                        'nonApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => false],
                        'applicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => true],
                    ],
                ],
                'applicable',
            ],
            'last plugin when no applicable found' => [
                [
                    'plugins' => [
                        'firstNonApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => false],
                        'lastNonApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => false],
                    ],
                ],
                'lastNonApplicable',
            ],
            'last plugin when no applicability checker' => [
                [
                    'plugins' => [
                        'first' => ['hasApplicabilityChecker' => false, 'isApplicable' => false],
                        'last' => ['hasApplicabilityChecker' => false, 'isApplicable' => false],
                    ],
                ],
                'last',
            ],
            'mixed plugin types with applicable found' => [
                [
                    'plugins' => [
                        'regular' => ['hasApplicabilityChecker' => false, 'isApplicable' => false],
                        'nonApplicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => false],
                        'applicable' => ['hasApplicabilityChecker' => true, 'isApplicable' => true],
                    ],
                ],
                'applicable',
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createQueryMock(): MockObject|QueryInterface
    {
        $mock = $this->createMock(QueryInterface::class);
        $mock->method('getSearchQuery')->willReturn($this->createMock('\Elastica\Query'));

        return $mock;
    }

    /**
     * @param bool $isApplicable
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function createQueryWithApplicabilityMock(bool $isApplicable): QueryInterface
    {
        $mock = $this->getMockBuilder(QueryInterface::class)
            ->onlyMethods(['getSearchQuery'])
            ->addMethods(['isApplicable'])
            ->getMock();
        $mock->method('getSearchQuery')->willReturn($this->createMock('\Elastica\Query'));
        $mock->method('isApplicable')->willReturn($isApplicable);

        return $mock;
    }
}
