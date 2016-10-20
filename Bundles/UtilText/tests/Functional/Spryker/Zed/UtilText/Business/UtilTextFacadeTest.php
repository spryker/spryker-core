<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\UtilText\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\UtilText\Business\UtilTextFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Util
 * @group Business
 * @group UtilFacadeTest
 */
class UtilTextFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\UtilText\Business\UtilTextFacade
     */
    protected $utilTextFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilTextFacade = new UtilTextFacade();
    }

    /**
     * @return void
     */
    public function testGenerateSlug()
    {
        $slug = $this->utilTextFacade->generateSlug('A #value#, [to] Slug 8 times.');

        $expectedSlug = 'a-value-to-slug-8-times';

        $this->assertEquals($expectedSlug, $slug);
    }

}
