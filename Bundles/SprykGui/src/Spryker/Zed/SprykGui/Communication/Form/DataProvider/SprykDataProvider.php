<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LayerTransfer;
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
     * @param string $sprykName
     * @param \Generated\Shared\Transfer\ModuleTransfer|null $moduleTransfer
     *
     * @return array
     */
    public function getOptions(string $sprykName, ?ModuleTransfer $moduleTransfer = null): array
    {
        $options = [];
        $options['allow_extra_fields'] = true;
        $options['auto_initialize'] = false;

        if ($sprykName) {
            $options['spryk'] = $sprykName;
        }

        if ($sprykName && $moduleTransfer) {
            $options['module'] = $moduleTransfer;
            $options += $this->getOptionsBySprykDefinition($sprykName, $moduleTransfer);
        }

        return $options;
    }

    /**
     * @param string $sprykName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    protected function getOptionsBySprykDefinition(string $sprykName, ModuleTransfer $moduleTransfer): array
    {
        $sprykDefinition = $this->sprykGuiFacade->getSprykDefinitionByName($sprykName);
        if (isset($sprykDefinition[ModuleTransfer::APPLICATION])) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($sprykDefinition[ModuleTransfer::APPLICATION]);
            $moduleTransfer->setApplication($applicationTransfer);
        }
        if (isset($sprykDefinition[ModuleTransfer::LAYER])) {
            $layerTransfer = new LayerTransfer();
            $layerTransfer->setName($sprykDefinition[ModuleTransfer::LAYER]);
            $moduleTransfer->setLayer($layerTransfer);
        }

        $moduleTransfer = $this->sprykGuiFacade->buildOptions($moduleTransfer);
        $optionTransfer = $moduleTransfer->requireOptions()->getOptions();

        $sprykOptions = [];

        if (array_key_exists('input', $sprykDefinition['arguments']) || array_key_exists('constructorArguments', $sprykDefinition['arguments'])) {
            $argumentCollectionTransfer = $optionTransfer->getArgumentCollection();
            $sprykOptions['argumentChoices'] = $argumentCollectionTransfer->getArguments();
        }

        if (array_key_exists('output', $sprykDefinition['arguments'])) {
            $returnTypeCollectionTransfer = $optionTransfer->getReturnTypeCollection();
            $sprykOptions['outputChoices'] = $returnTypeCollectionTransfer->getReturnTypes();
        }

        return $sprykOptions;
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
