<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Util\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\Util\Business\UtilFacade;
use Spryker\Zed\Util\Persistence\UtilQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Util
 * @group Business
 * @group UtilFacadeTest
 */
class UtilFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\Util\Business\UtilFacade
     */
    protected $utilFacade;

    /**
     * @var \Spryker\Zed\Util\Persistence\UtilQueryContainer
     */
    protected $utilQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilFacade = new UtilFacade();
        $this->utilQueryContainer = new UtilQueryContainer();
    }

    /**
     * @return void
     */
    public function testGenerateSlug()
    {
        $slug = $this->utilFacade->generateSlug('A #value#, [to] Slug 8 times.');

        $expectedSlug = 'a-value-to-slug-8-times';

        $this->assertEquals($expectedSlug, $slug);
    }

}
