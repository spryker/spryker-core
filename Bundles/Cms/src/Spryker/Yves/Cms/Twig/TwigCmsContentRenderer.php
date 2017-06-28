<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Cms\Twig;

use Exception;
use Twig_Environment;
use Spryker\Shared\Log\LoggerTrait;

class TwigCmsContentRenderer implements TwigCmsContentRendererInterface
{

    use LoggerTrait;

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Twig_Environment $twigEnvironment
     */
    public function __construct(Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
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
     * @return string
     */
    protected function renderTwigContent(array $context, $content)
    {
        try {
            return $this->twigEnvironment->createTemplate($content)->render($context);
        } catch (Exception $exception) {
            $this->getLogger()->warning($exception->getMessage(), ['exception' => $exception]);
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
