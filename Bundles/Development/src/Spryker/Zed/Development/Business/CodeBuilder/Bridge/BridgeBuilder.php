<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeBuilder\Bridge;

use Symfony\Component\Filesystem\Filesystem;

class BridgeBuilder
{
    const TEMPLATE_INTERFACE = 'interface';
    const TEMPLATE_BRIDGE = 'bridge';

    /**
     * @var string
     */
    protected $bundleRootDirectory;

    /**
     * @param string $bundleRootDirectory
     */
    public function __construct($bundleRootDirectory)
    {
        $this->bundleRootDirectory = $bundleRootDirectory;
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function build($bundle, $toBundle)
    {
        $this->createFacadeInterface($bundle, $toBundle);
        $this->createFacadeBridge($bundle, $toBundle);
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    protected function createFacadeInterface($bundle, $toBundle)
    {
        $templateContent = $this->getInterfaceTemplateContent();
        $templateContent = $this->replacePlaceHolder($bundle, $toBundle, $templateContent);

        $this->saveFile($bundle, $templateContent, $bundle . 'To' . $toBundle . 'Interface.php');
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    protected function createFacadeBridge($bundle, $toBundle)
    {
        $templateContent = $this->getBridgeTemplateContent();
        $templateContent = $this->replacePlaceHolder($bundle, $toBundle, $templateContent);

        $this->saveFile($bundle, $templateContent, $bundle . 'To' . $toBundle . 'Bridge.php');
    }

    /**
     * @return string
     */
    protected function getInterfaceTemplateContent()
    {
        return $this->getTemplateContent(static::TEMPLATE_INTERFACE);
    }

    /**
     * @return string
     */
    protected function getBridgeTemplateContent()
    {
        return $this->getTemplateContent(static::TEMPLATE_BRIDGE);
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getTemplateContent($templateName)
    {
        return file_get_contents(
            __DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . $templateName . '.tpl'
        );
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     * @param string $templateContent
     *
     * @return string
     */
    protected function replacePlaceHolder($bundle, $toBundle, $templateContent)
    {
        $templateContent = str_replace(
            ['{bundle}', '{toBundle}', '{toBundleVariable}'],
            [$bundle, $toBundle, lcfirst($toBundle)],
            $templateContent
        );

        return $templateContent;
    }

    /**
     * @param string $bundle
     * @param string $templateContent
     * @param string $fileName
     *
     * @return void
     */
    protected function saveFile($bundle, $templateContent, $fileName)
    {
        $path = $this->getPathToDependencyFiles($bundle);

        $filesystem = new Filesystem();
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

        if (!is_file($filePath)) {
            $filesystem->dumpFile($filePath, $templateContent);
        }
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getPathToDependencyFiles($bundle)
    {
        $pathParts = [$this->bundleRootDirectory, $bundle, 'src', 'Spryker', 'Zed', $bundle, 'Dependency', 'Facade'];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }
}
