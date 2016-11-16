<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ProductCategory;

use Codeception\Scenario;

class ZedAcceptanceTester extends AcceptanceTester
{

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $i = $this;
        $i->amZed();
        $i->amLoggedInUser();
    }

}
