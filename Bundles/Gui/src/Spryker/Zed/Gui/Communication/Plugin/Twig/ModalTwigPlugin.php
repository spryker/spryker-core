<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class ModalTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_MODAL = 'modal';

    /**
     * {@inheritDoc}
     * - Extends twig with "modal" function to add dialogs to your site for lightboxes, user notifications, or completely custom content.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getZedModalFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getZedModalFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_MODAL, function (string $title, string $content, ?string $footer = null, ?array $extraData = null) use ($twig) {
            return $twig->render(
                $this->getConfig()->getDefaultModalTemplatePath(),
                [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $footer,
                    'extras' => $this->getExtraData($extraData),
                ]
            );
        }, ['is_safe' => ['html']]);
    }

    /**
     * @param array|null $extraData
     *
     * @return string
     */
    protected function getExtraData(?array $extraData = null): string
    {
        $extras = '';

        if ($extraData) {
            foreach ($extraData as $key => $value) {
                $extras .= ' ' . $key . '="' . htmlentities($value) . '"';
            }
        }

        return $extras;
    }
}
