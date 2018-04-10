<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Symfony\Component\Finder\SplFileInfo;

class BundleBuilder implements BundleBuilderInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[]
     */
    protected $bundleMethodBuilders;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder\BundleMethodBuilderInterface[] $bundleMethodBuilders
     * @param array $options
     */
    public function __construct(array $bundleMethodBuilders, array $options)
    {
        $this->bundleMethodBuilders = $bundleMethodBuilders;
        $this->options = $options;
    }

    /**
     * @param string $baseDirectory
     * @param \Symfony\Component\Finder\SplFileInfo $bundleDirectory
     *
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    public function buildFromDirectory($baseDirectory, SplFileInfo $bundleDirectory)
    {
        $bundleTransfer = new IdeAutoCompletionBundleTransfer();
        $bundleTransfer->setName($bundleDirectory->getBasename());
        $bundleTransfer->setNamespaceName($this->getNamespace());
        $bundleTransfer->setMethodName(lcfirst($bundleDirectory->getBasename()));
        $path = rtrim($bundleDirectory->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $bundleTransfer->setDirectory($path);
        $bundleTransfer->setBaseDirectory($baseDirectory);

        $this->hydrateMethods($bundleTransfer);

        return $bundleTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return void
     */
    protected function hydrateMethods(IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        foreach ($this->bundleMethodBuilders as $methodBuilder) {
            $methodTransfer = $methodBuilder->getMethod($bundleTransfer);

            if ($methodTransfer) {
                $bundleTransfer->addMethod($methodTransfer);
            }
        }
    }
}
