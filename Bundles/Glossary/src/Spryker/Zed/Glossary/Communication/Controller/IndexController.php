<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getFactory()
            ->createEnabledLocales();

        $table = $this->getFactory()
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
        $availableLocales = $this->getFactory()
            ->createEnabledLocales();

        $table = $this->getFactory()
            ->createTranslationTable($availableLocales);

        return $this->jsonResponse($table->fetchData());
    }

}
