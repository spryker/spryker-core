<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

use Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriterInterface;
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
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriterInterface
     */
    protected $fileWriter;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\FileWriterInterface $fileWriter
     * @param array $options
     */
    public function __construct(Environment $twig, FileWriterInterface $fileWriter, array $options)
    {
        $this->twig = $twig;
        $this->fileWriter = $fileWriter;
        $this->options = $options;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $moduleTransferCollection
     *
     * @return void
     */
    public function generate(array $moduleTransferCollection): void
    {
        $templateVariables = [
            'bundleTransferCollection' => $moduleTransferCollection,
            'namespace' => $this->getNamespace(),
        ];

        $fileName = sprintf('%s.php', $this->getName());
        $content = $this->twig->render($this->getTemplateName(), $templateVariables);

        $this->fileWriter->writeFile($fileName, $content, $this->options);
    }

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return sprintf('%s.twig', $this->getName());
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return str_replace(
            IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
            $this->options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
            $this->options[IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN]
        );
    }
}
