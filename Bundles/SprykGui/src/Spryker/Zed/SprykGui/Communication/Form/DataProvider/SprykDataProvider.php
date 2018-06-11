<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface;

class SprykDataProvider
{
    /**
     * @var \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface
     */
    protected $sprykGuiFacade;

    /**
     * @param \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface $sprykGuiFacade
     */
    public function __construct(SprykGuiFacadeInterface $sprykGuiFacade)
    {
        $this->sprykGuiFacade = $sprykGuiFacade;
    }

    /**
     * @param null|string $selectedSpryk
     *
     * @return array
     */
    public function getOptions(?string $selectedSpryk = null): array
    {
        $options = [];
        $options['allow_extra_fields'] = true;

        if ($selectedSpryk) {
            $options['spryk'] = $selectedSpryk;
        }

        return $options;
    }

    /**
     * @param string $spryk
     * @param \Generated\Shared\Transfer\ModuleTransfer|null $moduleTransfer
     *
     * @return array
     */
    public function getData(string $spryk, ?ModuleTransfer $moduleTransfer = null): array
    {
        if (!$moduleTransfer) {
            $moduleTransfer = new ModuleTransfer();
        }

        $formData = [
            'spryk' => $spryk,
            'module' => $moduleTransfer,
        ];

        return $this->addSprykDefinitionDefaultData($formData, $spryk);
    }

    /**
     * @param array $formData
     * @param string $spryk
     *
     * @return array
     */
    protected function addSprykDefinitionDefaultData(array $formData, string $spryk): array
    {
        $sprykDefinition = $this->sprykGuiFacade->getSprykDefinitionByName($spryk);

        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['default'])) {
                $formData[$argumentName] = $argumentDefinition['default'];
            }
        }

        return $formData;
    }
}
