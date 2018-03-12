<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Service;

use Symfony\Component\HttpFoundation\Request;

class StepEngine
{

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[] $formPlugins
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function filterFormPlugins($formPlugins, Request $request)
    {
        $filteredPlugins = [];

        foreach($formPlugins as $formPlugin) {
            $filteredPlugins[] = $formPlugin;
            if (!$this->isFormValid($formPlugin, $request)) {
                break;
            }
        }

        return $filteredPlugins;
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface $formPlugin
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isFormValid($formPlugin, $request)
    {
        $form = $formPlugin->createForm($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            return true;
        }

        return false;
    }

}
