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
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     *
     * @api
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getZedModalFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getZedModalFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_MODAL, function (string $title, string $content, ?string $footer = null, ?array $extraData = null) {
            $extras = '';

            if (is_array($extraData)) {
                foreach ($extraData as $key => $value) {
                    $extras .= ' ' . $key . '="' . htmlentities($value) . '"';
                }
            }

            $html = '<div ' . $extras . '>';
            $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';
            $html .= '<header class="modal-header">';
            $html .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            $html .= '<h4 class="modal-title">' . $title . '</h4>';
            $html .= '</header>';
            $html .= '<div class="modal-body">' . $content . '</div>';

            if ($footer) {
                $html .= '<footer class="modal-footer">' . $footer . '</footer>';
            }

            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }, ['is_safe' => ['html']]);
    }
}
