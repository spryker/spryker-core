<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Model\Elasticsearch\Writer;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Model
 * @group Elasticsearch
 * @group Writer
 * @group WriterTest
 * Add your own group annotations below this line
 */
class WriterTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Search\SearchClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetIndices()
    {
        $writer = $this->tester->getFactory()->createWriter();
        $writer->getIndices();
    }
}
