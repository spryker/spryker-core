<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication\Provider;

use Generated\Shared\Transfer\OrderTransfer;
use Symfony\Component\Form\FormInterface;

class ReturnCreateTemplateProvider implements ReturnCreateTemplateProviderInterface
{
    protected const FIELD_RETURN_CREATE_FORM = 'returnCreateForm';
    protected const FIELD_ORDER = 'order';

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
     * @phpstan-param \Symfony\Component\Form\FormInterface<mixed> $returnCreateForm
     *
     * @phpstan-return array<string, mixed>
     *
     * @param \Symfony\Component\Form\FormInterface $returnCreateForm
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function provide(FormInterface $returnCreateForm, OrderTransfer $orderTransfer): array
    {
        if (count($this->returnCreateTemplatePlugins) < 1) {
            return [];
        }

        $templateData = [
            static::FIELD_RETURN_CREATE_FORM => $returnCreateForm->createView(),
            static::FIELD_ORDER => $orderTransfer,
        ];

        $templates = [];

        foreach ($this->returnCreateTemplatePlugins as $returnCreateTemplatePlugin) {
            $templates[$returnCreateTemplatePlugin->getTemplatePath()] = array_merge(
                $templateData,
                $returnCreateTemplatePlugin->getTemplateData($orderTransfer)
            );
        }

        return $templates;
    }
}
