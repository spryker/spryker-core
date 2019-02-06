<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase;

use Codeception\Test\Unit;
use Spryker\Client\StorageDatabase\StorageDatabaseFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group StorageDatabase
 * @group ResourceToTableMapperTest
 * Add your own group annotations below this line
 */
class ResourceToTableMapperTest extends Unit
{
    /**
     * @var \Spryker\Client\StorageDatabase\ResourceToTableMapper\ResourceToTableResolverInterface
     */
    private $resourceToTableResolver;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->resourceToTableResolver = (new StorageDatabaseFactory())->createResourceToTableResolver();
    }

    /**
     * @dataProvider getResourcePrefixToTableNameData
     *
     * @param string $resourcePrefix
     * @param string $tableName
     * @param bool $isCorrect
     *
     * @return void
     */
    public function testTableNamesAreResolvedCorrectly(string $resourcePrefix, string $tableName, bool $isCorrect): void
    {
        $resolvedTableName = $this->resourceToTableResolver->resolve($resourcePrefix);
        $this->assertEquals($isCorrect, $resolvedTableName === $tableName);
    }

    /**
     * @see
     *
     * @return array
     */
    public function getResourcePrefixToTableNameData(): array
    {
        return [
            'translation correct mapping' => ['translation', 'spy_glossary_storage', true],
            'translation incorrect mapping' => ['translation', 'spy_translation_storage', false],
            'product search config correct mapping' => ['product_search_config_extension', 'spy_product_search_config_storage', true],
            'abstract product list correct mapping' => ['product_abstract_product_lists', 'spy_product_abstract_product_list_storage', true],
            'concrete product list correct mapping' => ['product_concrete_product_lists', 'spy_product_concrete_product_list_storage', true],
            'availability incorrect mapping' => ['availability', 'spy_availabilities_storage', false],
        ];
    }
}
