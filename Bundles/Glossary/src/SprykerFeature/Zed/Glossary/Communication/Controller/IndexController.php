<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $availableLocales = $this->getDependencyContainer()
            ->createEnabledLocales()
        ;

        $grid = $this->getDependencyContainer()->createGlossaryKeyTranslationGrid($request);

        return [
            'locales' => $availableLocales,
            'grid' => $grid->renderData(),
        ];
    }

}
