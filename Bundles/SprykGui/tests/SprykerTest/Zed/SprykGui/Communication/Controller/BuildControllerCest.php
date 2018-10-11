<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SprykGui\Communication\Controller;

use SprykerTest\Zed\SprykGui\SprykGuiCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SprykGui
 * @group Communication
 * @group Controller
 * @group BuildControllerCest
 * Add your own group annotations below this line
 */
class BuildControllerCest
{
    /**
     * @param \SprykerTest\Zed\SprykGui\SprykGuiCommunicationTester $i
     *
     * @return void
     */
    public function openBuildSpryk(SprykGuiCommunicationTester $i)
    {
        $i->amOnPage('/spryk-gui/build?spryk=AddZedBusinessFacadeMethod');
        $i->seeResponseCodeIs(200);
        $i->seeBreadcrumbNavigation('Dashboard / SprykGui / Build Spryk');
        $i->see('Spryk', 'h2');
    }

    /**
     * @param \SprykerTest\Zed\SprykGui\SprykGuiCommunicationTester $i
     *
     * @return void
     */
    public function createSpryk(SprykGuiCommunicationTester $i)
    {
        $i->amOnPage('/spryk-gui/build?spryk=AddZedBusinessFacadeMethod');
        $i->seeResponseCodeIs(200);
        $i->seeBreadcrumbNavigation('Dashboard / SprykGui / Build Spryk');
        $i->see('Spryk', 'h2');

        $input = [];
        $input[] = [
            'innerArgument' => 0,
            'variable' => '$fooBar',
            'defaultValue' => '',
        ];

        $formData = [
            'spryk_main_form' => [
                'module' => 'SprykGui',
                'organization' => 'Spryker',
                'sprykDetails' => [
                    'comment' => "Line one.\r\nLine two.",
                    'method' => 'addFooBar',
                    'input' => $input,
                    'output' => 'bool',
                ],
                'create' => true,
            ],
        ];
        $i->submitForm(['name' => 'spryk_main_form'], $formData);

        $i->seeResponseCodeIs(200);
        $i->see('Jira Template', 'h3');
    }
}
