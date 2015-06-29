<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Communication;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use SprykerFeature\Zed\Glossary\Communication\Grid\TranslationGrid;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;

class UrlDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @param Request $request
     *
     * @return UrlGrid
     */
    public function createUrlGrid(Request $request)
    {
        return $this->getFactory()->createGridUrlGrid(
            $this->getQueryContainer()->queryUrls(),
            $request
        );
    }

    /**
     * @return UrlQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->url()->queryContainer();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getUrlForm(Request $request)
    {
        return $this->getFactory()->createFormUrlForm(
            $request,
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getDemoForm(Request $request)
    {
        return $this->getFactory()->createFormDemoForm(
            $request,
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return TranslationGrid
     */
    public function getUrlKeyTranslationGrid(Request $request)
    {
        $urlQueryContainer = $this->getQueryContainer();
        $translationQuery = $urlQueryContainer->joinLocales();

        return $this->getFactory()->createGridTranslationGrid(
            $translationQuery,
            $request
        );
    }

    /**
     * @return LocaleFacade
     */
    public function getLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }
}
