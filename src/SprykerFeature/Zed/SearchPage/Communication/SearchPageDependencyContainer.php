<?php

namespace SprykerFeature\Zed\SearchPage\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPageCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\SearchPage\Business\SearchPageFacade;
use SprykerFeature\Zed\SearchPage\Communication\Form\PageElementForm;
use SprykerFeature\Zed\SearchPage\Communication\Grid\PageElementGrid;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SearchPageCommunication getFactory()
 */
class SearchPageDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return SearchPageFacade
     */
    public function getSearchPageFacade()
    {
        return $this->getLocator()->searchPage()->facade();
    }

    /**
     * @param Request $request
     *
     * @return PageElementForm
     */
    public function createPageElementForm(Request $request)
    {
        return $this->getFactory()->createFormPageElementForm(
            $request,
            $this->getQueryContainer()
        );
    }

    /**
     * @param Request $request
     *
     * @return PageElementGrid
     */
    public function createPageElementGrid(Request $request)
    {
        return $this->getFactory()->createGridPageElementGrid(
            $this->getQueryContainer()->queryPageElementGrid(),
            $request
        );
    }

    /**
     * @return SearchPageQueryContainer
     */
    private function getQueryContainer()
    {
        return $this->getLocator()->searchPage()->queryContainer();
    }
}
