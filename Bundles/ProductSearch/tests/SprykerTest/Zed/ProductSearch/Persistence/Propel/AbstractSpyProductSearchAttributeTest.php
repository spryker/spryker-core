<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Persistence\Propel;

use Codeception\Test\Unit;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;
use Propel\Runtime\Propel;

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
    protected const PRODUCT_SEARCH_ATTRIBUTE_FILTER_TYPE = 'product_search_attribute';

    /**
     * @var \SprykerTest\Zed\ProductSearch\ProductSearchPersistenceTester
     */
    protected $tester;

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

        $this->spyProductSearchAttribute = new SpyProductSearchAttribute();
        $this->connection = Propel::getConnection();
    }

    /**
     * @return void
     */
    public function testPreInsertWithoutPositionShouldSetPosition(): void
    {
        // Arrange
        $productAttributeSearchEntity = $this->tester->createProductSearchAttribute(static::PRODUCT_SEARCH_ATTRIBUTE_FILTER_TYPE);
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
}
