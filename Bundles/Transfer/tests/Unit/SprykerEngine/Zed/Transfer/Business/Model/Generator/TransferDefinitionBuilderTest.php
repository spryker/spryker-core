<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
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

        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $directories);
        $transferDefinitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        $result = $transferDefinitionBuilder->getDefinitions();

        $this->assertTrue(is_array($result));

        $transferDefinition = $result[0];
        $this->assertInstanceOf('SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition', $transferDefinition);
    }


}
