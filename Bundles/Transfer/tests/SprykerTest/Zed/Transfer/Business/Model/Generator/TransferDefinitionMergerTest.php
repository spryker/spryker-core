<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group TransferDefinitionMergerTest
 * Add your own group annotations below this line
 */
class TransferDefinitionMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testMergeShouldReturnMergedTransferDefinition()
    {
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getTransferDefinition1(),
            $helper->getTransferDefinition2(),
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();

        $merger = new TransferDefinitionMerger();
        $this->assertEquals($expected, $merger->merge($transferDefinitions));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Value mismatch for "Transfer.type" tranfer property. Value1: "int"; Value2: "string". To fix this, search for 'property name="type"' in the code base and fix the wrong one.
     *
     * @return void
     */
    public function testMergeShouldThrowExceptionIfTwoPropertiesWithSameNameDefineDifferentAttributes()
    {
        $helper = new TransferDefinitionMergerHelper();
        $property1 = $helper->getTransferDefinition1();

        $property1['property'] = [
            [
                'name' => 'propertyA',
                'type' => 'int',
            ],
        ];

        $transferDefinitions = [
            $property1,
            $helper->getTransferDefinition2(),
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();

        $merger = new TransferDefinitionMerger();

        $merger->merge($transferDefinitions);
    }
}
