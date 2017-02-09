<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Navigation\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Navigation\Business\NavigationFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group NavigationFacadeTest
 */
class NavigationFacadeTest extends Test
{

    /**
     * @var NavigationfacadeInterface
     */
    protected $navigationFacade;

    public function setUp()
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
    }

    /**
     * @return void
     */
    public function testCreateNewNavigationPersistsToDatabase()
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer
            ->setKey('test-navigation-1')
            ->setName('Test navigation 1')
            ->setIsActive(true);

        $navigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $navigationTransfer->getIdNavigation());
    }

//    /**
//     * @return void
//     */
//    public function testUpdateExistingNavigationPersistsToDatabase()
//    {
//    }
//
//    /**
//     * @return void
//     */
//    public function testReadExistingNavigationReadsFromDatabase()
//    {
//    }
//
//    /**
//     * @return void
//     */
//    public function testDeleteExistingNavigationDeletesFromDatabase()
//    {
//    }

}
