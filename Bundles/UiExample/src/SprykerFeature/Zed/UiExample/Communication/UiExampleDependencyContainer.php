<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\UiExampleCommunication;
use SprykerFeature\Zed\UiExample\Communication\Form\CarForm;
use SprykerFeature\Zed\UiExample\Communication\Form\CopterForm;
use SprykerFeature\Zed\UiExample\Communication\Form\UiExampleForm;
use SprykerFeature\Zed\UiExample\Persistence\UiExampleQueryContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method UiExampleCommunication getFactory()
 */
class UiExampleDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param Request $request
     *
     * @return UiExampleForm
     */
    public function getUiExampleForm(Request $request)
    {
        return $this->getFactory()->createFormUiExampleForm(
            $request,
            $this
        );
    }

    /**
     * @param Request $request
     *
     * @return CarForm
     */
    public function getCarForm(Request $request)
    {
        return $this->getFactory()->createFormCarForm(
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return CopterForm
     */
    public function getCopterForm(Request $request)
    {
        return $this->getFactory()->createFormCopterForm(
            $request
        );
    }

    /**
     * @param Request $request
     *
     * @return object
     */
    public function getUiExampleGrid(Request $request)
    {
        return $this->getFactory()->createGridUiExampleGrid(
            $this->getQueryContainer()->queryUiExample(),
            $request
        );
    }

    /**
     * @return UiExampleQueryContainer
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->uiExample()->queryContainer();
    }

}
