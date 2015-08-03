<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getDependencyContainer()
            ->createEnabledLocales()
        ;

        $table = $this->getDependencyContainer()
            ->createTranslationTable($availableLocales)
        ;

        return $this->viewResponse([
            'locales' => $availableLocales,
            'glossaryTable' => $table->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $availableLocales = $this->getDependencyContainer()
            ->createEnabledLocales()
        ;

        $table = $this->getDependencyContainer()
            ->createTranslationTable($availableLocales)
        ;

        return $this->jsonResponse($table->fetchData());
    }
}
