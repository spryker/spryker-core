<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilCsv;

use Codeception\Test\Unit;
use Spryker\Service\UtilCsv\UtilCsvService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilCsv
 * @group Facade
 * @group UtilCsvFacadeTest
 * Add your own group annotations below this line
 */
class UtilCsvFacadeTest extends Unit
{
    /**
     * @var \Spryker\Service\UtilCsv\UtilCsvServiceInterface
     */
    protected $utilCsvFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->utilCsvFacade = new UtilCsvService();
    }
}
