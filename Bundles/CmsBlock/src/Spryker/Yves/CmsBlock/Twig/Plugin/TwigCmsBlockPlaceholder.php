<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsBlock\Twig\Plugin;

use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Client\CmsBlock\CmsBlockClientInterface getClient()
 * @method \Spryker\Yves\CmsBlock\CmsBlockFactory getFactory()
 */
class TwigCmsBlockPlaceholder extends AbstractPlugin implements TwigFunctionPluginInterface
{
    public const CMS_BLOCK_PREFIX_KEY = 'generated.cms.cms-block';

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        return [
            new Twig_SimpleFunction('spyCmsBlockPlaceholder', function (array $context, $identifier) use ($application) {
                $placeholders = $context['placeholders'];

                $translation = '';
                if (isset($placeholders[$identifier])) {
                    $translation = $placeholders[$identifier];
                }

                if ($this->isGlossaryKey($translation)) {
                    $translator = $this->getTranslator($application);
                    $translation = $translator->trans($translation);
                }

                if ($this->isGlossaryKey($translation)) {
                    $translation = '';
                }

                return $this->renderCmsTwigContent($translation, $identifier, $context);
            }, ['needs_context' => true]),
        ];
    }

    /**
     * @param string $translation
     * @param string $identifier
     * @param array $context
     *
     * @return string
     */
    protected function renderCmsTwigContent($translation, $identifier, array $context)
    {
        $twigRenderedPlugin = $this->getFactory()->getCmsBlockTwigContentRendererPlugin();
        if (!$twigRenderedPlugin) {
            return $translation;
        }

        $renderedTwigContent = $twigRenderedPlugin->render([$identifier => $translation], $context);
        return $renderedTwigContent[$identifier];
    }

    /**
     * @param string $translation
     *
     * @return bool
     */
    protected function isGlossaryKey($translation)
    {
        return strpos($translation, static::CMS_BLOCK_PREFIX_KEY) === 0;
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator(Application $application)
    {
        return $application['translator'];
    }
}
