<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsBlock\Plugin\Twig;

use Spryker\Yves\Twig\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @method \Spryker\Yves\CmsBlock\CmsBlockFactory getFactory()
 */
class CmsBlockPlaceholderTwigPlugin extends AbstractTwigExtensionPlugin
{
    public const CMS_BLOCK_PREFIX_KEY = 'generated.cms.cms-block';

    protected const SPY_CMS_BLOCK_PLACEHOLDER_TWIG_FUNCTION = 'spyCmsBlockPlaceholder';
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(static::SPY_CMS_BLOCK_PLACEHOLDER_TWIG_FUNCTION, function (array $context, $identifier) {
                $translation = $this->getTranslation($identifier, $context);

                return $this->renderCmsTwigContent($translation, $identifier, $context);
            }, ['needs_context' => true]),
        ];
    }

    /**
     * @param string $identifier
     * @param array $context
     *
     * @return string
     */
    protected function getTranslation(string $identifier, array $context): string
    {
        $placeholders = $context['placeholders'];

        $translation = '';
        if (isset($placeholders[$identifier])) {
            $translation = $placeholders[$identifier];
        }

        if ($this->isGlossaryKey($translation)) {
            $translator = $this->getTranslator();
            $translation = $translator->trans($translation);
        }

        if ($this->isGlossaryKey($translation)) {
            $translation = '';
        }

        return $translation;
    }

    /**
     * @param string $translation
     * @param string $identifier
     * @param array $context
     *
     * @return string
     */
    protected function renderCmsTwigContent(string $translation, string $identifier, array $context): string
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
    protected function isGlossaryKey(string $translation): bool
    {
        return strpos($translation, static::CMS_BLOCK_PREFIX_KEY) === 0;
    }

    /**
     * @return mixed
     */
    protected function getTranslator()
    {
        return $this->getApplication()->get(static::SERVICE_TRANSLATOR);
    }
}
