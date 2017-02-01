<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class CreateGlossaryController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';
    const SEARCH_LIMIT = 50;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsGlossaryTransfer = $this->getFactory()
            ->getCmsFacade()
            ->getPageGlossaryAttributes($idCmsPage);

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsGlossaryFormDataProvider = $this->getFactory()
            ->createCmsGlossaryFormDataProvider($availableLocales, $cmsGlossaryTransfer);

        $placeholderTabs = $this->getFactory()
            ->createPlaceholderTabs($cmsGlossaryTransfer);

        $glossaryForm = $this->getFactory()
            ->createCmsGlossaryForm($cmsGlossaryFormDataProvider);

        $glossaryForm->handleRequest($request);

        if ($glossaryForm->isSubmitted()) {
            if ($glossaryForm->isValid()) {
                $cmsGlossaryTransfer = $this->getFactory()
                    ->getCmsFacade()
                    ->saveCmsGlossary($glossaryForm->getData());

                $this->addSuccessMessage('Placeholder translations successfully updated.');
            } else {
                $this->addErrorMessage('Invalid data provided.');
            }
        }

        return [
            'glossaryForm' => $glossaryForm->createView(),
            'placeholderTabs' => $placeholderTabs->createView(),
            'availableLocales' => $availableLocales,
            'cmsGlossary' => $cmsGlossaryTransfer,
            'idCmsPage' => $idCmsPage,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $value = $request->query->get('value');
        $key = $request->query->get('key');

        $result = [];
        if ($key !== null) {
            $glossaryKeys = $this->getFactory()
                ->getCmsQueryContainer()
                ->queryKeyWithTranslationByKey($key)
                ->limit(static::SEARCH_LIMIT)
                ->find();

            foreach ($glossaryKeys as $glossaryKeyEntity) {

                $translations = [];
                foreach ($glossaryKeyEntity->getSpyGlossaryTranslations() as $glossaryTranslationEntity) {
                    $translations[$glossaryTranslationEntity->getFkLocale()] = $glossaryTranslationEntity->toArray();
                }

                $result[] = [
                    'key' => $glossaryKeyEntity->getLabel(),
                    'translations' => $translations,
                ];
            }

        } else {
            $glossaryTranslations = $this->getFactory()
                ->getCmsQueryContainer()
                ->queryTranslationWithKeyByValue($value)
                ->limit(static::SEARCH_LIMIT)
                ->find();

            foreach ($glossaryTranslations as $glossaryTranslationEntity) {
                if (!isset($result[$glossaryTranslationEntity->getLabel()])) {
                    $result[$glossaryTranslationEntity->getLabel()] = [
                        'key' => $glossaryTranslationEntity->getLabel(),
                        'translations' => [],
                    ];
                }

                $result[$glossaryTranslationEntity->getLabel()]['translations'][] = $glossaryTranslationEntity->toArray();
            }
        }

        return $this->jsonResponse($result);
    }

}
