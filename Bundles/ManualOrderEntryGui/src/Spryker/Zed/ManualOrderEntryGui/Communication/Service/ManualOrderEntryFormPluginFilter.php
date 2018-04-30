<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Service;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class ManualOrderEntryFormPluginFilter
{
    /**
     * @var string
     */
    protected $prevStepName;

    /**
     * @var string
     */
    protected $nextStepName;

    /**
     * @param string $prevStepName
     * @param string $nextStepName
     */
    public function __construct($prevStepName, $nextStepName)
    {
        $this->prevStepName = $prevStepName;
        $this->nextStepName = $nextStepName;
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    public function getFilteredFormPlugins($formPlugins, Request $request, QuoteTransfer $quoteTransfer): array
    {
        $filteredPlugins = [];
        $skippedPlugins = [];
        $isPrevFormPreFilled = false;

        foreach ($formPlugins as $formPlugin) {
            $pluginAdded = false;

            if ($this->isFormSkipped($formPlugin, $request, $quoteTransfer)) {
                $skippedPlugins[] = $formPlugin;
                continue;
            }

            $isFormSubmitted = $this->isFormSubmitted($formPlugin, $request);
            $isShowNext = $this->isShowNext($request);
            $isFormPreFilled = $this->isFormPreFilled($formPlugin, $quoteTransfer);

            if ($isShowNext || $isPrevFormPreFilled) {
                $filteredPlugins[] = $formPlugin;
                $pluginAdded = true;
            }

            if (!$isFormSubmitted && !$isFormPreFilled) {
                break;
            }

            if (!$pluginAdded) {
                $filteredPlugins[] = $formPlugin;
            }
            $isPrevFormPreFilled = $isFormPreFilled;
        }

        $filteredPlugins = $this->augmentFilteredPlugins($formPlugins, $filteredPlugins, $skippedPlugins);

        if ($this->isShowPrev($request) && count($filteredPlugins) > 1) {
            array_pop($filteredPlugins);
        }

        return $filteredPlugins;
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    public function getSkippedFormPlugins($formPlugins, Request $request, QuoteTransfer $quoteTransfer)
    {
        $skippedPlugins = [];

        foreach ($formPlugins as $formPlugin) {
            if ($this->isFormSkipped($formPlugin, $request, $quoteTransfer)) {
                $skippedPlugins[] = $formPlugin;
                continue;
            }

            $isFormSubmitted = $this->isFormSubmitted($formPlugin, $request);
            $isFormPreFilled = $this->isFormPreFilled($formPlugin, $quoteTransfer);

            if (!$isFormSubmitted && !$isFormPreFilled) {
                break;
            }
        }

        return $skippedPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isShowNext(Request $request): bool
    {
        return $request->request->get($this->nextStepName) !== null;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isShowPrev(Request $request): bool
    {
        return $request->request->get($this->prevStepName) !== null;
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface $formPlugin
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isFormPreFilled(ManualOrderEntryFormPluginInterface $formPlugin, QuoteTransfer $quoteTransfer): bool
    {
        return $formPlugin->isFormPreFilled($quoteTransfer);
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface $formPlugin
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isFormSubmitted(ManualOrderEntryFormPluginInterface $formPlugin, Request $request): bool
    {
        return ($request->request->get($formPlugin->getName()) !== null);
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface $formPlugin
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isFormSkipped(ManualOrderEntryFormPluginInterface $formPlugin, Request $request, QuoteTransfer $quoteTransfer): bool
    {
        return $formPlugin->isFormSkipped($request, $quoteTransfer);
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $filteredPlugins
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $skippedPlugins
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    protected function augmentFilteredPlugins($formPlugins, $filteredPlugins, $skippedPlugins): array
    {
        if (!count($filteredPlugins)) {
            foreach ($formPlugins as $formPlugin) {
                $isSkipped = false;

                foreach ($skippedPlugins as $skippedPlugin) {
                    if ($formPlugin->getName() == $skippedPlugin->getName()) {
                        $isSkipped = true;
                        break;
                    }
                }

                if (!$isSkipped) {
                    $filteredPlugins[] = $formPlugin;
                    break;
                }
            }
        }

        return $filteredPlugins;
    }
}
