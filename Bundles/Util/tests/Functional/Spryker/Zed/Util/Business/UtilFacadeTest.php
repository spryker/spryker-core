<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Util\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Spryker\Zed\Util\Business\UtilFacade;
use Spryker\Zed\Util\Persistence\UtilQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Util
 * @group Business
 * @group UtilFacadeAttributeTest
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

    const SLUG_VALUE = 'A #value#, [to] Slugify it 8 times.';

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->utilFacade = new UtilFacade();
        $this->utilQueryContainer = new UtilQueryContainer();
    }


    public function testSlugify()
    {
        $slug = $this->utilFacade->slugify(self::SLUG_VALUE);

        $expectedSlug = 'a-value-to-slugify-it-8-times';

        $this->assertEquals($expectedSlug, $slug);
    }


}
