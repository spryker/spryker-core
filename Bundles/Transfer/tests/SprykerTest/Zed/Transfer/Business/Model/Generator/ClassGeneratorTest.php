<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group ClassGeneratorTest
 * Add your own group annotations below this line
 */
class ClassGeneratorTest extends Unit
{

    /**
     * @return void
     */
    public function setUp()
    {
        $this->removeTargetDirectory();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $this->removeTargetDirectory();
    }

    /**
     * @return void
     */
    private function removeTargetDirectory()
    {
        if (is_dir($this->getFixtureDirectory())) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getFixtureDirectory());
        }
    }

    /**
     * @return string
     */
    private function getFixtureDirectory()
    {
        return __DIR__ . '/FixturesTest/';
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateTargetDirectoryIfNotExist()
    {
        $transferGenerator = new DataBuilderClassGenerator($this->getFixtureDirectory());
        $transferDefinition = new DataBuilderDefinition();
        $transferDefinition->setDefinition([
            'name' => 'Name',
        ]);
        $transferGenerator->generate($transferDefinition);

        $this->assertTrue(is_dir($this->getFixtureDirectory()));
    }

}
