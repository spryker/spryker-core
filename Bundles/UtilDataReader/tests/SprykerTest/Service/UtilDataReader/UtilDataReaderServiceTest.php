<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDataReader;

use Codeception\Test\Unit;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Service\UtilDataReader\UtilDataReaderService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDataReader
 * @group UtilDataReaderServiceTest
 * Add your own group annotations below this line
 */
class UtilDataReaderServiceTest extends Unit
{
    /**
     * @return void
     */
    public function testGetYamlBatchIteratorReturnsCountableIterator()
    {
        $utilDataReaderService = new UtilDataReaderService();
        $yamlBatchIterator = $utilDataReaderService->getYamlBatchIterator('fileName');

        $this->assertInstanceOf(CountableIteratorInterface::class, $yamlBatchIterator);
    }
}
