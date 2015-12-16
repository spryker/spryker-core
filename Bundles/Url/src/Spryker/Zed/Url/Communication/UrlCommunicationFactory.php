<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Url\Communication\Form\UrlForm;
use Spryker\Zed\Url\Communication\Grid\TranslationGrid;
use Spryker\Zed\Url\Communication\Grid\UrlGrid;
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @method UrlQueryContainerInterface getQueryContainer()
 */
class UrlCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return UrlGrid
     */
    public function createUrlGrid()
    {
        return new UrlGrid();
    }

    /**
     * @return UrlForm
     */
    public function getUrlForm()
    {
        return new UrlForm();
    }

    /**
     * @return TranslationGrid
     */
    public function getUrlKeyTranslationGrid()
    {
        return new TranslationGrid();
    }

}
