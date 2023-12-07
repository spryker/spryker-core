<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Storage;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Zed\Collector\Business\Storage\StorageInstanceBuilder;
use Spryker\Zed\Collector\CollectorConfig;
use SprykerTest\Zed\Collector\CollectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Collector
 * @group Business
 * @group Storage
 * @group StorageInstanceBuilderTest
 * Add your own group annotations below this line
 */
class StorageInstanceBuilderTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Search\SearchConstants::ELASTICA_PARAMETER__TRANSPORT
     *
     * @var string
     */
    protected const SEARCH_PARAMETER_TRANSPORT = 'ELASTICA_PARAMETER__TRANSPORT';

    /**
     * @uses \Spryker\Shared\Search\SearchConstants::ELASTICA_PARAMETER__PORT
     *
     * @var string
     */
    protected const SEARCH_PARAMETER_PORT = 'ELASTICA_PARAMETER__PORT';

    /**
     * @uses \Spryker\Shared\Search\SearchConstants::ELASTICA_PARAMETER__HOST
     *
     * @var string
     */
    protected const SEARCH_PARAMETER_HOST = 'ELASTICA_PARAMETER__HOST';

    /**
     * @var string
     */
    protected const ELASTICSEARCH_INSTANCE_CONFIG_KEY_TRANSPORT = 'transport';

    /**
     * @var string
     */
    protected const ELASTICSEARCH_INSTANCE_CONFIG_KEY_PORT = 'port';

    /**
     * @var string
     */
    protected const ELASTICSEARCH_INSTANCE_CONFIG_KEY_HOST = 'host';

    /**
     * @var \SprykerTest\Zed\Collector\CollectorBusinessTester
     */
    protected CollectorBusinessTester $tester;

    /**
     * @dataProvider getElasticsearchInstanceDataProvider
     *
     * @param list<string|null> $searchElasticsearchConfiguration
     * @param list<string|null> $searchConfiguration
     * @param list<string> $expectedInstanceConfiguration
     *
     * @return void
     */
    public function testGetElasticsearchInstance(
        array $searchElasticsearchConfiguration,
        array $searchConfiguration,
        array $expectedInstanceConfiguration
    ): void {
        // Arrange
        [$transport, $port, $host] = $searchElasticsearchConfiguration;
        $this->tester->mockEnvironmentConfig(CollectorConfig::SEARCH_ELASTICSEARCH_PARAMETER_TRANSPORT, $transport);
        $this->tester->mockEnvironmentConfig(CollectorConfig::SEARCH_ELASTICSEARCH_PARAMETER_PORT, $port);
        $this->tester->mockEnvironmentConfig(CollectorConfig::SEARCH_ELASTICSEARCH_PARAMETER_HOST, $host);

        [$transport, $port, $host] = $searchConfiguration;
        $this->tester->mockEnvironmentConfig(static::SEARCH_PARAMETER_TRANSPORT, $transport);
        $this->tester->mockEnvironmentConfig(static::SEARCH_PARAMETER_PORT, $port);
        $this->tester->mockEnvironmentConfig(static::SEARCH_PARAMETER_HOST, $host);

        [$transport, $port, $host] = $expectedInstanceConfiguration;

        $searchInstances = (new ReflectionClass(new StorageInstanceBuilder()))->getProperty('searchInstances');
        $searchInstances->setAccessible(true);
        $searchInstances->setValue([]);
        $searchInstances->setAccessible(false);

        // Act
        $elasticsearchInstanceConfig = StorageInstanceBuilder::getElasticsearchInstance()->getConfig();

        // Assert
        $this->assertSame($transport, $elasticsearchInstanceConfig[static::ELASTICSEARCH_INSTANCE_CONFIG_KEY_TRANSPORT]);
        $this->assertSame($port, $elasticsearchInstanceConfig[static::ELASTICSEARCH_INSTANCE_CONFIG_KEY_PORT]);
        $this->assertSame($host, $elasticsearchInstanceConfig[static::ELASTICSEARCH_INSTANCE_CONFIG_KEY_HOST]);
    }

    /**
     * @return array<array<list<string|null>>>
     */
    protected function getElasticsearchInstanceDataProvider(): array
    {
        return [
            [
                [null, null, null], ['http', '777', 'remote'], ['Http', '777', 'remote'],
            ],
            [
                ['https', '999', 'localhost'], ['http', '777', 'remote'], ['Https', '999', 'localhost'],
            ],
            [
                ['https', '999', 'localhost'], [null, null, null], ['Https', '999', 'localhost'],
            ],
        ];
    }
}
