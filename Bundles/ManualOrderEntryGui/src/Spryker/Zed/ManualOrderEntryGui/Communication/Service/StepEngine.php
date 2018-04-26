<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Service;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class StepEngine
{
    /**
     * @var string
     */
    protected $nextStepName;

    /**
     * @param string $nextStepName
     */
    public function __construct($nextStepName)
    {
        $this->nextStepName = $nextStepName;
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function filterFormPlugins($formPlugins, Request $request, QuoteTransfer $quoteTransfer)
    {
        $filteredPlugins = [];
        $isFormPreFilledLast = false;

        foreach ($formPlugins as $formPlugin) {
            $pluginAdded = false;
            $isFormSubmitted = $this->isFormSubmitted($formPlugin, $request);
            $isShowNext = $this->isShowNext($request);
            $isFormPreFilled = $this->isFormPreFilled($formPlugin, $quoteTransfer);

            if ($isShowNext || $isFormPreFilledLast) {
                $filteredPlugins[] = $formPlugin;
                $pluginAdded = true;
            }

            if (!$isFormSubmitted && !$isFormPreFilled) {
                break;
            }

            if (!$pluginAdded) {
                $filteredPlugins[] = $formPlugin;
            }
            $isFormPreFilledLast = $isFormPreFilled;
        }

        $filteredPlugins = $this->augmentFilteredPlugins($formPlugins, $filteredPlugins);

        return $filteredPlugins;
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
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $filteredPlugins
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    protected function augmentFilteredPlugins($formPlugins, $filteredPlugins): array
    {
        if (count($formPlugins) && !count($filteredPlugins)) {
            $filteredPlugins[] = array_shift($formPlugins);
        }

        return $filteredPlugins;
    }
}
