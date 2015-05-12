<?php

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class SandboxController extends AbstractController
{
    public function createUrlsAction()
    {
        $array1 = $this->createUrl1();
        $array2 = $this->createUrl2();

        return $this->jsonResponse(['page' => $array1, 'redirect' => $array2]);
    }

    protected function createUrl1()
    {
        $urlFacade = $this->getLocator()->url()->facade();
        $cmsFacade = $this->getLocator()->cms()->facade();
        $glossaryFacade = $this->getLocator()->glossary()->facade();
        $localeFacade = $this->getLocator()->locale()->facade();

        if (!$localeFacade->hasLocale('de_DE')) {
            $localeFacade->createLocale('de_DE');
        }

        if (!$glossaryFacade->hasKey('TestKey1')) {
            $key1Id = $glossaryFacade->createKey('TestKey1');
        } else {
            $key1Id = $glossaryFacade->getKeyIdentifier('TestKey1');
        }
        if (!$glossaryFacade->hasTranslation('TestKey1', 'de_DE')) {
            $glossaryFacade->createTranslation('TestKey1', 'de_DE', 'Translation1 Bla', true);
        }

        if (!$glossaryFacade->hasKey('TestKey2')) {
            $key2Id = $glossaryFacade->createKey('TestKey2');
        } else {
            $key2Id = $glossaryFacade->getKeyIdentifier('TestKey2');
        }
        if (!$glossaryFacade->hasTranslation('TestKey2', 'de_DE')) {
            $glossaryFacade->createTranslation('TestKey2', 'de_DE', 'Translation2 Bla', true);
        }

        $templatePath = '@cms/template/playground.twig';

        if ($cmsFacade->hasTemplate($templatePath)) {
            $cmsTemplate = $cmsFacade->getTemplate($templatePath);
        } else {
            $cmsTemplate = $cmsFacade->createTemplate('Template1', $templatePath);
        }

        $cmsPage = new \Generated\Shared\Transfer\CmsPageTransfer();
        $cmsPage->setFkTemplate($cmsTemplate->getIdCmsTemplate());
        $cmsPage->setIsActive(true);

        $cmsPage = $cmsFacade->savePage($cmsPage);
        $cmsFacade->touchPageActive($cmsPage);

        $urlString = '/aktion/ssv1';

        if (!$urlFacade->hasUrl($urlString)) {
            $cmsFacade->createPageUrl($cmsPage, $urlString);
        }

        $mappings = [];

        if (!$cmsFacade->hasPagePlaceholderMapping($cmsPage->getIdCmsPage(), 'Placeholder1')) {
            $mapping1 = new \Generated\Shared\Transfer\CmsPageKeyMappingTransfer();
        } else {
            $mapping1 = $cmsFacade->getPagePlaceholderMapping($cmsPage->getIdCmsPage(), 'Placeholder1');
        }
        $mapping1->setFkPage($cmsPage->getIdCmsPage());
        $mapping1->setFkGlossaryKey($key1Id);
        $mapping1->setPlaceholder('Placeholder1');
        $cmsFacade->savePageKeyMapping($mapping1);
        $mappings[] = $mapping1->toArray();

        if (!$cmsFacade->hasPagePlaceholderMapping($cmsPage->getIdCmsPage(), 'Placeholder2')) {
            $mapping2 = new \Generated\Shared\Transfer\CmsPageKeyMappingTransfer();
        } else {
            $mapping2 = $cmsFacade->getPagePlaceholderMapping($cmsPage->getIdCmsPage(), 'Placeholder2');
        }
        $mapping2->setFkPage($cmsPage->getIdCmsPage());
        $mapping2->setFkGlossaryKey($key2Id);
        $mapping2->setPlaceholder('Placeholder2');
        $cmsFacade->savePageKeyMapping($mapping2);
        $mappings[] = $mapping2->toArray();

        return ['relations' => $cmsPage->toArray(), 'mappings' => $mappings];
    }

    protected function createUrl2()
    {
        $urlFacade = $this->getLocator()->url()->facade();
        $localeFacade = $this->getLocator()->locale()->facade();

        $urlString1 = '/aktion/ssv1';
        $urlString2 = '/aktion/ssv2';

        $redirect = $urlFacade->createRedirect($urlString1);
        $urlFacade->touchRedirectActive($redirect);

        if (!$urlFacade->hasUrl($urlString2)) {
            $urlFacade->createRedirectUrl($urlString2, $localeFacade->getCurrentLocale(), $redirect->getIdRedirect());
        }

        return $redirect->toArray();
    }

    public function touchUrlsAction()
    {
        $urlFacade = $this->getLocator()->url()->facade();

        $urlStrings = ['/aktion/ssv1', '/aktion/ssv2'];
        $result = ['touched' => false, 'date' => new \DateTime()];

        foreach ($urlStrings as $urlString) {
            if ($urlFacade->hasUrl($urlString)) {
                $urlFacade->touchUrlActive($urlFacade->getUrlByPath($urlString)->getIdUrl());
                $result['touched'] = true;
            }
        }

        return $this->jsonResponse($result);
    }
}
