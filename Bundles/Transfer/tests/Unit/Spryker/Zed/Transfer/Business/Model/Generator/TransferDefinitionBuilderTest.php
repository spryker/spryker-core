<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Transfer\Business\Model\Generator;

use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;

/**
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferDefinitionBuilder
 */
class TransferDefinitionBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildTransferDefinitionShouldReturnArrayWithClassDefinitions()
    {
        $directories = [
            __DIR__ . '/Fixtures/Project/',
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
        $this->assertInstanceOf('Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition', $transferDefinition);
    }

}
