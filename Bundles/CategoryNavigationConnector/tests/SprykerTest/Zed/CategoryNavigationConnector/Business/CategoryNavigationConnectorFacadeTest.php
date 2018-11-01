<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryNavigationConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NodeTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CategoryNavigationConnector
 * @group Business
 * @group Facade
 * @group CategoryNavigationConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CategoryNavigationConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CategoryNavigationConnector\CategoryNavigationConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetNavigationNodeToActiveWhenCategoryIsActive()
    {
        $this->setUpNavigationNodeCategoryTest(true);
    }

    /**
     * @return void
     */
    public function testSetNavigationNodeToInactiveWhenCategoryIsInactive()
    {
        $this->setUpNavigationNodeCategoryTest(false);
    }

    /**
     * @param bool $isActive
     *
     * @return void
     */
    protected function setUpNavigationNodeCategoryTest($isActive)
    {
        // Arrange
        $locale = $this->tester->haveLocale();
        $category = $this->tester->haveLocalizedCategory([ 'locale' => $locale, CategoryTransfer::CATEGORY_NODE => [ NodeTransfer::IS_ROOT => false ], CategoryTransfer::IS_ACTIVE => $isActive ]);
        $navigation = $this->tester->haveNavigation();

        /** @var \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface $urlQueryContainer */
        $urlQueryContainer = $this->tester->getLocator()->url()->queryContainer();
        $urls = $urlQueryContainer->queryUrls()->filterByFkResourceCategorynode($category->getCategoryNode()->getIdCategoryNode())->find();

        $navigationNodes = [];

        foreach ($urls as $url) {
            $navigationNodes[] = $this->tester->haveLocalizedNavigationNode([
                NavigationNodeTransfer::FK_NAVIGATION => $navigation->getIdNavigation(),
                NavigationNodeLocalizedAttributesTransfer::FK_URL => $url->getIdUrl(),
                NavigationNodeLocalizedAttributesTransfer::FK_LOCALE => $locale->getIdLocale(),
                NavigationNodeTransfer::IS_ACTIVE => !$isActive,
            ]);
        }

        // Act
        $this->tester->getFacade()->updateCategoryNavigationNodesIsActive($category);

        // Assert

        /** @var \Spryker\Zed\Navigation\Business\NavigationFacadeInterface $navigationFacade */
        $navigationFacade = $this->tester->getLocator()->navigation()->facade();
        foreach ($navigationNodes as $navigationNode) {
            $navigationNode = $navigationFacade->findNavigationNode($navigationNode);
            $this->assertSame($isActive, $navigationNode->getIsActive());
        }
    }
}
