<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsBlock\Twig\Plugin;

use DateTime;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Client\CmsBlock\CmsBlockClientInterface getClient()
 */
class TwigCmsBlock extends AbstractPlugin implements TwigFunctionPluginInterface
{

    const OPTION_NAME = 'name';
    const POSITION_POSTFIX = '_position';

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application)
    {
        $this->localeName = $application['locale'];

        return [
            new Twig_SimpleFunction('spyCmsBlock', [
                $this,
                'renderCmsBlock',
            ], [
                'needs_context' => true,
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param array $blockOptions
     *
     * @return string
     */
    public function renderCmsBlock(Twig_Environment $twig, array $context, array $blockOptions = [])
    {
        $blocks = $this->getBlockDataByOptions($blockOptions);
        $rendered = '';

        foreach ($blocks as $blockData) {
            $isActive = $this->validateBlock($blockData);
            $isActive &= $this->validateDates($blockData);

            if ($isActive) {
                $rendered .= $twig->render($blockData['template'], [
                    'placeholders' => $blockData['placeholders'],
                ]);
            }
        }

        return $rendered;
    }

    /**
     * @param array $blockOptions
     *
     * @return array
     */
    protected function getBlockDataByOptions(array &$blockOptions)
    {
        $blockNameKey = $this->extractBlockNameKey($blockOptions);
        $blockOptions = $this->preparePositionOptions($blockOptions);
        $availableBlockNames = $this->getClient()->findBlockNamesByOptions($blockOptions, $this->localeName);
        $availableBlockNames = $this->filterAvailableBlockNames($blockNameKey, $availableBlockNames);

        return $this->getClient()->findBlocksByNames($availableBlockNames, $this->localeName);
    }

    /**
     * @param array $blockOptions
     *
     * @return array
     */
    protected function preparePositionOptions($blockOptions)
    {
        foreach ($blockOptions as $optionKey => $optionValue) {
            if (strpos($optionKey, static::POSITION_POSTFIX) !== false) {
                $resourceName = rtrim($optionKey, static::POSITION_POSTFIX);

                if (isset($blockOptions[$resourceName])) {
                    $blockOptions[$optionKey] = $optionValue . $blockOptions[$resourceName];
                } else {
                    unset($blockOptions[$optionKey]);
                }
            }
        }

        return $blockOptions;
    }

    /**
     * @param string $blockNameKey
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterAvailableBlockNames($blockNameKey, array $availableBlockNames)
    {
        if ($blockNameKey) {
            if (!$availableBlockNames || in_array($blockNameKey, $availableBlockNames)) {
                $availableBlockNames = [$blockNameKey];
            } else {
                $availableBlockNames = [];
            }
        }

        return $availableBlockNames;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function createBlockTransfer()
    {
        $cmsBlockTransfer = new CmsBlockTransfer();

        return $cmsBlockTransfer;
    }

    /**
     * @param array $blockOptions
     *
     * @return string
     */
    protected function extractBlockNameKey(array &$blockOptions)
    {
        $blockName = isset($blockOptions[static::OPTION_NAME]) ? $blockOptions[static::OPTION_NAME] : null;
        unset($blockOptions[static::OPTION_NAME]);

        return $this->getClient()->generateBlockNameKey($blockName, $this->localeName);
    }

    /**
     * @param array $cmsBlockData
     *
     * @return bool
     */
    protected function validateBlock($cmsBlockData)
    {
        return !($cmsBlockData === null);
    }

    /**
     * @param array $cmsBlockData
     *
     * @return bool
     */
    protected function validateDates(array $cmsBlockData)
    {
        $dateToCompare = new DateTime();

        if (isset($cmsBlockData['valid_from'])) {
            $validFrom = new DateTime($cmsBlockData['valid_from']);

            if ($dateToCompare < $validFrom) {
                return false;
            }
        }

        if (isset($cmsBlockData['valid_to'])) {
            $validTo = new DateTime($cmsBlockData['valid_to']);

            if ($dateToCompare > $validTo) {
                return false;
            }
        }

        return true;
    }

}
