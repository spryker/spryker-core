<?php

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferDefinitionBuilder
 */
class TransferDefinitionBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildTransferDefinitionShouldReturnArrayWithClassDefinitions()
    {
        $directories = [
            __DIR__ . '/Fixtures/Project/'
        ];

        $transferDefinitionBuilder = new TransferDefinitionBuilder(
            new TransferDefinitionMerger(),
            $directories
        );
        $result = $transferDefinitionBuilder->getTransferDefinitions();

        $this->assertTrue(is_array($result));

        $transferDefinition = $result['NameOfTransfer'];
        $this->assertInstanceOf('SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition', $transferDefinition);
    }


}
