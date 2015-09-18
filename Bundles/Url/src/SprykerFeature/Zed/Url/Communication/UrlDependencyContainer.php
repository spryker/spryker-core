<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\UrlCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Url\Communication\Form\UrlForm;
use SprykerFeature\Zed\Url\Communication\Grid\TranslationGrid;
use SprykerFeature\Zed\Url\Communication\Grid\UrlGrid;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @method UrlCommunication getFactory()
 * @method UrlQueryContainerInterface getQueryContainer()
 */
class UrlDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return UrlGrid
     */
    public function createUrlGrid()
    {
        return $this->getFactory()->createGridUrlGrid();
    }

    /**
     * @return UrlForm
     */
    public function getUrlForm()
    {
        return $this->getFactory()->createFormUrlForm();
    }

    /**
     * @return TranslationGrid
     */
    public function getUrlKeyTranslationGrid()
    {
        return $this->getFactory()->createGridTranslationGrid();
    }

}
