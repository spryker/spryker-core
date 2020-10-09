<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Persistence\Propel;

use Codeception\Test\Unit;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;
use Propel\Runtime\Propel;
use Spryker\Zed\ProductSearch\Persistence\Propel\AbstractSpyProductSearchAttribute;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Persistence
 * @group Propel
 * @group AbstractSpyProductSearchAttributeTest
 * Add your own group annotations below this line
 */
class AbstractSpyProductSearchAttributeTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\Propel\AbstractSpyProductSearchAttribute
     */
    protected $spyProductSearchAttribute;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->spyProductSearchAttribute = new class extends AbstractSpyProductSearchAttribute {
        };
        $this->connection = Propel::getConnection();
    }

    /**
     * @return void
     */
    public function testPreInsertWithoutPositionShouldSetPosition(): void
    {
        // Arrange
        $productAttributeSearchEntity = $this->createProductSearchAttribute('product_search_attribute');
        $expectedPosition = $productAttributeSearchEntity->getPosition() + 1;
        $this->spyProductSearchAttribute->setPosition(0);

        // Act
        $result = $this->spyProductSearchAttribute->preInsert($this->connection);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($expectedPosition, $this->spyProductSearchAttribute->getPosition());
    }

    /**
     * @return void
     */
    public function testPreInsertWithPositionShouldSkipSetPosition(): void
    {
        // Arrange
        $position = 1;
        $this->spyProductSearchAttribute->setPosition($position);

        // Act
        $result = $this->spyProductSearchAttribute->preInsert($this->connection);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($position, $this->spyProductSearchAttribute->getPosition());
    }

    /**
     * @param string $filterType
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute
     */
    protected function createProductSearchAttribute(string $filterType): SpyProductSearchAttribute
    {
        $productAttributeKey = new SpyProductAttributeKey();
        $productAttributeKey->setKey("{$filterType}_key");
        $productAttributeKey->save();

        $productSearchAttribute = new SpyProductSearchAttribute();
        $productSearchAttribute->setFilterType($filterType);
        $productSearchAttribute->setFkProductAttributeKey($productAttributeKey->getIdProductAttributeKey());
        $productSearchAttribute->save();

        return $productSearchAttribute;
    }
}
