<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Twig\Environment;

abstract class AbstractGenerator implements GeneratorInterface
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Twig\Environment $twig
     * @param array $options
     */
    public function __construct(Environment $twig, array $options)
    {
        $this->twig = $twig;
        $this->options = $options;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $moduleTransferCollection
     *
     * @return string
     */
    public function generate(array $moduleTransferCollection)
    {
        $templateVariables = [
            'bundleTransferCollection' => $moduleTransferCollection,
            'namespace' => $this->getNamespace(),
        ];

        return $this->twig->render($this->getTemplateName(), $templateVariables);
    }

    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return sprintf('%s.twig', $this->getName());
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        return str_replace(
            IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
            $this->options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
            $this->options[IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN]
        );
    }
}
