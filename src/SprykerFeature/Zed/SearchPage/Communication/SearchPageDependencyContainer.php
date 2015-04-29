<?php

namespace SprykerFeature\Zed\SearchPage\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\SearchPageCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\SearchPage\Business\SearchPageFacade;
use SprykerFeature\Zed\SearchPage\Communication\Form\PageElementForm;
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
     * @return PageElementForm
     */
    public function createPageElementPage(Request $request)
    {
        return $this->getFactory()->createFormPageElementForm(
            $request,
            $this->getLocator()
        );
    }
}
