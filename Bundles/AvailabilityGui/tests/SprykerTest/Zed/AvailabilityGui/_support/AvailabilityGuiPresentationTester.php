<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui;

use Codeception\Actor;
use Codeception\Scenario;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class AvailabilityGuiPresentationTester extends Actor
{
    use _generated\AvailabilityGuiPresentationTesterActions;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amZed();
        $this->amLoggedInUser();
    }

    /**
     * @return void
     */
    public function assertTableWithDataExists()
    {
        $showingEntries = $this->grabTextFrom('//*[@class="dataTables_info"]');
        preg_match('/^Showing\s{1}\d+\s{1}to\s{1}(\d+)/', $showingEntries, $matches);
        $this->assertGreaterThan(0, (int)$matches[1]);

        $td = $this->grabTextFrom('//*[@class="dataTables_scrollBody"]/table/tbody');
        $itemListItems = count(explode("\n", $td));

        $this->assertGreaterThan(0, $itemListItems);
    }

    /**
     * @return void
     */
    public function clickViewButton()
    {
        $this->click("//*[@class=\"dataTables_scrollBody\"]/table/tbody/tr/td[8]/a");
    }
}
