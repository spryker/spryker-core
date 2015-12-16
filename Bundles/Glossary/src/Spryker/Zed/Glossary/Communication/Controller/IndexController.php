<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Communication\GlossaryDependencyContainer;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryDependencyContainer getCommunicationFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getCommunicationFactory()
            ->createEnabledLocales();

        $table = $this->getCommunicationFactory()
            ->createTranslationTable($availableLocales);

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
        $availableLocales = $this->getCommunicationFactory()
            ->createEnabledLocales();

        $table = $this->getCommunicationFactory()
            ->createTranslationTable($availableLocales);

        return $this->jsonResponse($table->fetchData());
    }

}
