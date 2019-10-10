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
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `SprykerShop\Yves\CmsBlockWidget\Plugin\Twig\TwigCmsBlockWidgetPlugin` instead.
 *
 * @method \Spryker\Client\CmsBlock\CmsBlockClientInterface getClient()
 */
class TwigCmsBlock extends AbstractPlugin implements TwigFunctionPluginInterface
{
    public const OPTION_NAME = 'name';
    public const OPTION_POSITION = 'position';

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(Application $application)
    {
        $this->localeName = $application['locale'];

        return [
            new TwigFunction('spyCmsBlock', [
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
     * @param \Twig\Environment $twig
     * @param array $context
     * @param array $blockOptions
     *
     * @return string
     */
    public function renderCmsBlock(Environment $twig, array $context, array $blockOptions = [])
    {
        $blocks = $this->getBlockDataByOptions($blockOptions);
        $rendered = '';

        foreach ($blocks as $blockData) {
            $isActive = $this->validateBlock($blockData);
            $isActive &= $this->validateDates($blockData);

            if ($isActive) {
                $rendered .= $twig->render($blockData['template'], [
                    'placeholders' => $blockData['placeholders'],
                    'cmsContent' => $blockData,
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
        $blockName = $this->extractBlockNameOption($blockOptions);
        $positionName = $this->extractPositionOption($blockOptions);

        $availableBlockNames = $this->getClient()->findBlockNamesByOptions($blockOptions, $this->localeName);
        $availableBlockNames = $this->filterPosition($positionName, $availableBlockNames);
        $availableBlockNames = $this->filterAvailableBlockNames($blockName, $availableBlockNames);

        return $this->getClient()->findBlocksByNames($availableBlockNames, $this->localeName);
    }

    /**
     * @param array $blockOptions
     *
     * @return string
     */
    protected function extractPositionOption(array &$blockOptions)
    {
        $positionName = isset($blockOptions[static::OPTION_POSITION]) ? $blockOptions[static::OPTION_POSITION] : '';
        $positionName = strtolower($positionName);
        unset($blockOptions[static::OPTION_POSITION]);

        return $positionName;
    }

    /**
     * @param string $positionName
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterPosition($positionName, array $availableBlockNames)
    {
        if (is_array(current($availableBlockNames))) {
            return isset($availableBlockNames[$positionName]) ? $availableBlockNames[$positionName] : [];
        }

        return $availableBlockNames;
    }

    /**
     * @param string $blockName
     * @param array $availableBlockNames
     *
     * @return array
     */
    protected function filterAvailableBlockNames($blockName, array $availableBlockNames)
    {
        $blockNameKey = $this->generateBlockNameKey($blockName);

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
    protected function extractBlockNameOption(array &$blockOptions)
    {
        $blockName = isset($blockOptions[static::OPTION_NAME]) ? $blockOptions[static::OPTION_NAME] : null;
        unset($blockOptions[static::OPTION_NAME]);

        return $blockName;
    }

    /**
     * @param array|null $cmsBlockData
     *
     * @return bool
     */
    protected function validateBlock($cmsBlockData)
    {
        return !($cmsBlockData === null);
    }

    /**
     * @param string $blockName
     *
     * @return string
     */
    protected function generateBlockNameKey($blockName)
    {
        return $this->getClient()->generateBlockNameKey($blockName, $this->localeName);
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
