<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetChartConnector\Plugin;

use Spryker\Shared\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetChartConnector\CmsContentWidgetChartConnectorFactory getFactory()
 */
class CmsChartContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
{
    /**
     * @var \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected $widgetConfiguration;

    /**
     * @param \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface $widgetConfiguration
     */
    public function __construct(CmsContentWidgetConfigurationProviderInterface $widgetConfiguration)
    {
        $this->widgetConfiguration = $widgetConfiguration;
    }

    /**
     * @return callable
     */
    public function getContentWidgetFunction()
    {
        return [$this, 'contentWidgetFunction'];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param array|string $chartAbstractSkuList $chartAbstractSkuList
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, $chartAbstractSkuList, $templateIdentifier = null)
    {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $chartAbstractSkuList)
        );
    }

    /**
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier = null)
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param array $context
     * @param array|string $chartKeyList
     *
     * @return array
     */
    protected function getContent(array $context, $chartKeyList)
    {
        $cmsContent = $this->getCmsContent($context);
        $keyMap = $this->getChartKeyMap($cmsContent);

        if (is_array($chartKeyList)) {
            return ['chart' => array_shift($keyMap)];
        }

        return ['chart' => $keyMap];
    }

    /**
     * @param array $cmsContent
     *
     * @return array
     */
    protected function getChartKeyMap(array $cmsContent)
    {
        return $cmsContent[CmsContentWidgetConfig::CMS_CONTENT_WIDGET_PARAMETER_MAP][$this->widgetConfiguration->getFunctionName()];
    }

    /**
     * @param array $context
     *
     * @return array
     */
    protected function getCmsContent(array $context)
    {
        return $context['cmsContent'];
    }
}
