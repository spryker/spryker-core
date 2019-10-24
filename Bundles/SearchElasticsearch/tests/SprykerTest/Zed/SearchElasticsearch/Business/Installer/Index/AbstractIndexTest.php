<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\SearchElasticsearch\tests\SprykerTest\Zed\SearchElasticsearch\Business\Installer\Index;

use Codeception\Test\Unit;
use Elastica\Client;
use Elastica\Index;
use Elastica\Type\Mapping;
use Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group SearchElasticsearch
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group SearchElasticsearch
 * @group Business
 * @group Installer
 * @group Index
 * @group AbstractIndexTest
 * Add your own group annotations below this line
 */
abstract class AbstractIndexTest extends Unit
{
    /**
     * @param string[] $mappings
     *
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMappingBuilderMock(array $mappings = []): MappingBuilderInterface
    {
        $mappingMock = $this->createMock(Mapping::class);
        $mappingMock->method('toArray')->willReturn($mappings);

        $mappingBuilder = $this->createMock(MappingBuilderInterface::class);
        $mappingBuilder->method('buildMapping')->willReturn($mappingMock);

        return $mappingBuilder;
    }

    /**
     * @param \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject|null $indexMock
     *
     * @return \Elastica\Client|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createClientMock(?Index $indexMock = null): Client
    {
        $clientMock = $this->createMock(Client::class);
        $clientMock->method('getIndex')->willReturn(
            $indexMock ?? $this->createIndexMock()
        );

        return $clientMock;
    }

    /**
     * @return \Elastica\Index|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createIndexMock(): Index
    {
        return $this->createMock(Index::class);
    }
}
