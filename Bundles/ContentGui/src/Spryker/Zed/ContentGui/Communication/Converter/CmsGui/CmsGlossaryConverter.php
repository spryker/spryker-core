<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Converter\CmsGui;

use DOMDocument;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;

class CmsGlossaryConverter implements CmsGlossaryConverterInterface
{
    /**
     * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    protected $contentEditorPlugins;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\ContentGuiConfig $config
     */
    public function __construct(array $contentEditorPlugins, ContentGuiToContentFacadeInterface $contentFacade, ContentGuiConfig $config)
    {
        $this->contentEditorPlugins = $contentEditorPlugins;
        $this->contentFacade = $contentFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $key => $cmsGlossaryAttributesTransfer) {
            $cmsGlossaryPlaceholderTranslations = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsGlossaryPlaceholderTranslations as $k => $cmsGlossaryPlaceholderTranslation) {
                $translation = $cmsGlossaryPlaceholderTranslation->getTranslation();

                foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
                    $pattern = str_replace(
                        ['(', ')', '%ID%,', '\'%TEMPLATE%\''],
                        ['\(', '\)', '[0-9]+,', '\'[a-z\-]+\''],
                        '/' . $contentEditorPlugin->getTwigFunctionTemplate() . '/'
                    );
                    preg_match($pattern, $translation, $twigFunctionsInTranslations);

                    foreach ($twigFunctionsInTranslations as $twigFunctionInTranslation) {
                        preg_match('/[0-9]+/', $twigFunctionInTranslation, $idContentItem);
                        preg_match('/\'[a-z\-]+\'/', $twigFunctionInTranslation, $templateIdentifier);

                        $contentItem = $this->contentFacade->findContentById((int)$idContentItem[0]);
                        ob_start();
                        include($this->config->getContentWidgetTemplatePath());
                        $contentWidget = ob_get_clean();
                        $contentWidget = str_replace(
                            ['%TYPE%', '%ID%', '%TEMPLATE%', '%TWIG_FUNCTION%'],
                            [$contentItem->getContentTypeKey(), $contentItem->getIdContent(), $templateIdentifier[0], $twigFunctionInTranslation],
                            $contentWidget
                        );

                        $translation = str_replace($twigFunctionInTranslation, $contentWidget, $translation);
                        $cmsGlossaryPlaceholderTranslations[$k]->setTranslation($translation);
                    }
                }
            }

            $cmsGlossaryAttributesTransfers[$key]->setTranslations($cmsGlossaryPlaceholderTranslations);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToTwig(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $key => $cmsGlossaryAttributesTransfer) {
            $cmsGlossaryPlaceholderTranslations = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsGlossaryPlaceholderTranslations as $k => $cmsGlossaryPlaceholderTranslation) {
                $translation = $cmsGlossaryPlaceholderTranslation->getTranslation();

                $dom = new DOMDocument();
                $dom->load($translation);

                foreach ($dom->getElementsByTagName('div') as $div) {
                    if (isset($div->{'data-twig-function'})) {
                        $translation = preg_replace(
                            '\<(div(.*)data\-twig\-function=' . $div->{'data-twig-function'} . ') \>(.*)<\/div>',
                            $div->{'data-twig-function'},
                            $translation
                        );
                    }
                    $cmsGlossaryPlaceholderTranslations[$k]->setTranslation($translation);
                }
            }
            $cmsGlossaryAttributesTransfers[$key]->setTranslations($cmsGlossaryPlaceholderTranslations);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }
}
