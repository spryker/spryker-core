<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SprykGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;

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
        $sprykDefinitions = $this->tester->getSprykGuiFacade()->getSprykDefinitions();
        $this->assertInternalType('array', $sprykDefinitions);
    }

    /**
     * @return void
     */
    public function testBuildSprykViewReturnsCommandAndJiraTemplate(): void
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName('Spryker')
            ->setRootPath(APPLICATION_ROOT_DIR . '/vendor/spryker/spryker/Bundles/%module%/');

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName('FooBar')
            ->setOrganization($organizationTransfer);

        $userInput = [
            'module' => $moduleTransfer,
            'method' => 'addFooBar',
            'input' => 'string $fooBar',
            'output' => 'bool',
            'comment' => "Specification:\r\n- Line one.\r\n- Line two.",
        ];
        $sprykView = $this->tester->getSprykGuiFacade()->buildSprykView('AddZedBusinessFacadeMethod', $userInput);
        $this->tester->assertCommandLine($sprykView);
        $this->tester->assertJiraTemplate($sprykView);
    }
}
