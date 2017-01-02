<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed\Tester;

use AvailabilityGui\ZedAcceptanceTester;

class AvailabilityTester extends ZedAcceptanceTester
{

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

}
