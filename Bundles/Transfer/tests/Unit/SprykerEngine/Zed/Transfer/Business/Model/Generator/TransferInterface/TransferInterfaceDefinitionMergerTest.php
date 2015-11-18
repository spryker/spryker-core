<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\TransferInterfaceDefinitionMerger;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferInterfaceDefinitionMerger
 */
class TransferInterfaceDefinitionMergerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testMergeShouldMergeWhenTransferNameAndContainingBundleNameIdentically()
    {
        $definitionOne = [
            'bundle' => 'BundleA',
            'containing bundle' => 'ContainingBundle',
            'name' => 'TransferName',
            'property' => [],
        ];
        $definitionTwo = [
            'bundle' => 'BundleB',
            'containing bundle' => 'ContainingBundle',
            'name' => 'TransferName',
            'property' => [],
        ];

        $merger = new TransferInterfaceDefinitionMerger();
        $mergedDefinitions = $merger->merge([$definitionOne, $definitionTwo]);

        $this->assertCount(1, $mergedDefinitions);
    }

    /**
     * @return void
     */
    public function testMergeShouldNotMergeWhenTransferNamesAreDifferentButContainingBundleNameIsTheSame()
    {
        $definitionOne = [
            'bundle' => 'BundleA',
            'containing bundle' => 'ContainingBundle',
            'name' => 'TransferA',
            'property' => [],
        ];
        $definitionTwo = [
            'bundle' => 'BundleB',
            'containing bundle' => 'ContainingBundle',
            'name' => 'TransferB',
            'property' => [],
        ];

        $merger = new TransferInterfaceDefinitionMerger();
        $mergedDefinitions = $merger->merge([$definitionOne, $definitionTwo]);

        $this->assertCount(2, $mergedDefinitions);
    }

    /**
     * @return void
     */
    public function testMergeShouldNotMergeWhenContainingBundleNamesAreDifferentButTransferNameIsTheSame()
    {
        $definitionOne = [
            'bundle' => 'BundleA',
            'containing bundle' => 'ContainingBundleA',
            'name' => 'TransferName',
            'property' => [],
        ];
        $definitionTwo = [
            'bundle' => 'BundleB',
            'containing bundle' => 'ContainingBundleB',
            'name' => 'TransferName',
            'property' => [],
        ];

        $merger = new TransferInterfaceDefinitionMerger();
        $mergedDefinitions = $merger->merge([$definitionOne, $definitionTwo]);

        $this->assertCount(2, $mergedDefinitions);
    }

}
