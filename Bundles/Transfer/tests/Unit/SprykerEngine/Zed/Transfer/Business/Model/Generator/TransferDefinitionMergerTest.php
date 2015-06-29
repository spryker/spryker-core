<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferDefinitionMerger
 */
class TransferDefinitionMergerTest extends \PHPUnit_Framework_TestCase
{

    public function testMergeShouldReturnMergedTransferDefinition()
    {
        $helper = new TransferDefinitionMergerHelper();
        $transferDefinitions = [
            $helper->getTransferDefinition1(),
            $helper->getTransferDefinition2()
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();

        $merger = new TransferDefinitionMerger();
        $this->assertEquals($expected, $merger->merge($transferDefinitions));
    }

    public function testMergeShouldThrowExceptionIfTwoPropertiesWithSameNameDefineDifferentAttributes()
    {
        $this->setExpectedException('\Exception', 'Property "propertyA" defined more than once with different attributes!');
        $helper = new TransferDefinitionMergerHelper();
        $property1 = $helper->getTransferDefinition1();

        $property1['property'] = [
            [
                'name' => 'propertyA',
                'type' => 'int'
            ]
        ];

        $transferDefinitions = [
            $property1,
            $helper->getTransferDefinition2()
        ];

        $expected = [];
        $expected['Transfer'] = $helper->getExpectedTransfer();

        $merger = new TransferDefinitionMerger();
        $merger->merge($transferDefinitions);
    }
}
