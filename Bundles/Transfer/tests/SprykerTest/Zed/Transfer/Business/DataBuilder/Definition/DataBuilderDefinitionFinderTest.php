<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\DataBuilder\Definition;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group DataBuilder
 * @group Definition
 * @group DataBuilderDefinitionFinderTest
 * Add your own group annotations below this line
 */
class DataBuilderDefinitionFinderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Transfer\TransferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetXmlTransferDefinitionFilesFindsDataBuilderDefinitions(): void
    {
        $transferDefinitions = $this->tester->createEntityTransferDefinitionFinder()->getXmlTransferDefinitionFiles();

        $this->assertCount(1, $transferDefinitions);
    }
}
