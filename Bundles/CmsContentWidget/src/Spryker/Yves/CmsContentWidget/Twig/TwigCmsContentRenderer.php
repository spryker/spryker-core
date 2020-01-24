<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidget\Twig;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\CmsContentWidget\CmsContentWidgetConfig;
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
     * @var \Spryker\Yves\CmsContentWidget\CmsContentWidgetConfig
     */
    protected $cmsContentWidgetConfig;

    /**
     * @param \Twig\Environment $twigEnvironment
     * @param \Spryker\Yves\CmsContentWidget\CmsContentWidgetConfig $cmsContentWidgetConfig
     */
    public function __construct(Environment $twigEnvironment, CmsContentWidgetConfig $cmsContentWidgetConfig)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->cmsContentWidgetConfig = $cmsContentWidgetConfig;
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
     * @throws \Throwable
     *
     * @return string
     */
    protected function renderTwigContent(array $context, $content)
    {
        try {
            return $this->twigEnvironment->createTemplate($content)->render($context);
        } catch (Throwable $exception) {
            if ($this->cmsContentWidgetConfig->isDebugModeEnabled()) {
                throw $exception;
            }

            $this->getLogger()->error($exception->getMessage(), ['exception' => $exception]);

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
