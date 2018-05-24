<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SprykGui\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SprykGui
 * @group Business
 * @group Facade
 * @group SprykGuiFacadeTest
 * Add your own group annotations below this line
 */
class SprykGuiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SprykGui\SprykGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetSprykDefinitionsReturnsListOfSpryks(): void
    {
        $sprykDefinitions = $this->tester->getFacade()->getSprykDefinitions();
        $this->assertInternalType('array', $sprykDefinitions);
    }

    /**
     * @return void
     */
    public function testBuildSprykViewReturnsCommandAndJiraTemplate(): void
    {
        $userInput['AddZedFacadeMethod'] = [
            'module' => 'FooBar',
            'moduleOrganization' => 'Spryker',
            'method' => 'addFooBar',
            'input' => 'string $fooBar',
            'output' => 'bool',
            'comment' => "Specification:\r\n- Line one.\r\n- Line two.",
        ];
        $sprykView = $this->tester->getFacade()->buildSprykView('AddZedFacadeMethod', $userInput);
        $this->tester->assertCommandLine($sprykView);
        $this->tester->assertJiraTemplate($sprykView);
    }
}
