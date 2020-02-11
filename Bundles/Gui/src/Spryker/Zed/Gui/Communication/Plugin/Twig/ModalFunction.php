<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\Gui\Communication\Plugin\Twig\ModalTwigPlugin` instead.
 */
class ModalFunction extends TwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'modal';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($title, $content, $footer = null, $extraData = null) {
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
        };
    }
}
