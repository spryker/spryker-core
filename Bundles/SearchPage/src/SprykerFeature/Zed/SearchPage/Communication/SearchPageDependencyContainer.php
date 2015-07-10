<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPageCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\SearchPage\Business\SearchPageFacade;
use SprykerFeature\Zed\SearchPage\Communication\Form\PageElementForm;
use SprykerFeature\Zed\SearchPage\Communication\Grid\PageElementGrid;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SearchPageCommunication getFactory()
 */
class SearchPageDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return SearchPageFacade
     */
    public function getSearchPageFacade()
    {
        return $this->getLocator()->searchPage()->facade();
    }

    /**
     * @return SearchPageQueryContainer
     */
    public function getSearchPageQueryContainer()
    {
        return $this->getLocator()->searchPage()->queryContainer();
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
            $this->getSearchPageQueryContainer()
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
            $this->getSearchPageQueryContainer()->queryPageElementGrid(),
            $request
        );
    }

}
