<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms\Twig;

use Exception;
use Spryker\Shared\Config\Environment;
use Twig_Environment;

class TwigCmsContentRenderer implements TwigCmsContentRendererInterface
{

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @var \Spryker\Shared\Config\Environment
     */
    protected $applicationEnvironment;

    /**
     * @param \Twig_Environment $twigEnvironment
     * @param \Spryker\Shared\Config\Environment $applicationEnvironment
     */
    public function __construct(
        Twig_Environment $twigEnvironment,
        Environment $applicationEnvironment
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->applicationEnvironment = $applicationEnvironment;
    }

    /**
     * @param array $contentList
     * @param array $context
     *
     * @return array
     */
    public function render(array $contentList, array $context)
    {
        $rendered = [];
        foreach ($contentList as $key => $content) {
            if ($this->isTwigContent($content)) {
                $rendered[$key] = $this->renderTwigContent($context, $content);
            } else {
                $rendered[$key] = $content;
            }
        }

        return $rendered;
    }

    /**
     * @param array $context
     * @param string $content
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function renderTwigContent(array $context, $content)
    {
        try {
            return $this->twigEnvironment->createTemplate($content)->render($context);
        } catch (Exception $exception) {
            if (!$this->applicationEnvironment->isProduction()) {
                throw $exception;
            }
            return $content;
        }
    }

    /**
     * @param string $content
     *
     * @return bool
     */
    protected function isTwigContent($content)
    {
        if (strpos($content, '{{') !== false && strpos($content, '}}') !== false) {
            return true;
        }

        return false;
    }

}
