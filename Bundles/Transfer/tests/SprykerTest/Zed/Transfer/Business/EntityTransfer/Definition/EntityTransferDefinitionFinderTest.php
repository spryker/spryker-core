<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\EntityTransfer\Definition;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group EntityTransfer
 * @group Definition
 * @group EntityTransferDefinitionFinderTest
 * Add your own group annotations below this line
 */
class EntityTransferDefinitionFinderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Transfer\TransferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetXmlTransferDefinitionFilesFindsEntityTransferDefinitions(): void
    {
        $transferDefinitions = $this->tester->createEntityTransferDefinitionFinder()->getXmlTransferDefinitionFiles();

        $this->assertCount(1, $transferDefinitions);
    }
}
