<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget\Twig;

use Spryker\Shared\Log\LoggerTrait;
use Throwable;
use Twig\Environment;

class TwigCmsContentRenderer implements TwigCmsContentRendererInterface
{
    use LoggerTrait;

    /**
     * @var \Twig\Environment
     */
    protected $twigEnvironment;

    /**
     * @param \Twig\Environment $twigEnvironment
     */
    public function __construct(Environment $twigEnvironment)
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
        } catch (Throwable $exception) {
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
