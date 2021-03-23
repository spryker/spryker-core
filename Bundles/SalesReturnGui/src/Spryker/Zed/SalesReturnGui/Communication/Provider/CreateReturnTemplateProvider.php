<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Provider;

use Generated\Shared\Transfer\OrderTransfer;
use Symfony\Component\Form\FormInterface;

class CreateReturnTemplateProvider implements CreateReturnTemplateProviderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateTemplatePluginInterface[]
     */
    protected $returnCreateTemplatePlugins;

    /**
     * @param \Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateTemplatePluginInterface[] $returnCreateTemplatePlugins
     */
    public function __construct(array $returnCreateTemplatePlugins)
    {
        $this->returnCreateTemplatePlugins = $returnCreateTemplatePlugins;
    }

    /**
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function provide(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): array
    {
        $templateData = [
            'returnCreateForm' => $returnCreateForm,
            'order' => $orderTransfer,
        ];

        if (empty($this->returnCreateTemplatePlugins)) {
            return [
                '@SalesReturnGui/SalesReturn/Create/default.twig' => $templateData,
            ];
        }

        foreach ($this->returnCreateTemplatePlugins as $returnCreateTemplatePlugin) {
            $templateData[$returnCreateTemplatePlugin->getTemplatePath()] = array_merge(
                $templateData,
                $returnCreateTemplatePlugin->getTemplateData($orderTransfer)
            );
        }

        return $templateData;
    }
}
