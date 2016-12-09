<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeBuilder\Bundle;

use Symfony\Component\Filesystem\Filesystem;
use Zend\Filter\FilterChain;

class BundleBuilder
{

    const TEMPLATE_INTERFACE = 'interface';
    const TEMPLATE_BRIDGE = 'bridge';

    /**
     * @var string
     */
    protected $bundleRootDirectory;

    /**
     * List of files to generate in each bundle.
     *
     * @var array
     */
    protected $files = [
        '.gitattributes',
        '.gitignore',
        '.coveralls.yml',
        '.codeception.yml',
        '.travis.yml',
        'CHANGELOG.md',
        'README.md',
        'LICENSE'
    ];

    /**
     * @param string $bundleRootDirectory
     */
    public function __construct($bundleRootDirectory)
    {
        $this->bundleRootDirectory = $bundleRootDirectory;
    }

    /**
     * @param string $bundle
     * @param array $options
     *
     * @return void
     */
    public function build($bundle, array $options)
    {
        $bundles = $this->getBundleNames();

        if ($bundle !== 'all') {
            $bundle = $this->getUnderscoreToCamelCaseFilter()->filter($bundle);
            $bundles = (array)$bundle;
        }

        foreach ($bundles as $bundle) {
            $this->createOrUpdateBundle($bundle, $options);
        }
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getUnderscoreToCamelCaseFilter()
    {
        $filter = new FilterChain();

        $filter->attachByName('WordUnderscoreToCamelCase');

        return $filter;
    }

    /**
     * @return array
     */
    protected function getBundleNames()
    {
        $bundleDirectories = glob($this->bundleRootDirectory . '*');

        $bundles = [];
        foreach ($bundleDirectories as $bundleDirectory) {
            $bundles[] = pathinfo($bundleDirectory, PATHINFO_BASENAME);
        }

        return $bundles;
    }

    /**
     * @param string $bundle
     * @param array $options
     *
     * @return void
     */
    protected function createOrUpdateBundle($bundle, $options)
    {
        $files = $this->files;

        foreach ($files as $file) {
            $templateContent = $this->getTemplateContent($file);

            $templateContent = $this->replacePlaceHolder($bundle, $templateContent);

            $this->saveFile($bundle, $templateContent, $file, $options['force']);
        }
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
     * @param string $templateContent
     *
     * @return string
     */
    protected function replacePlaceHolder($bundle, $templateContent)
    {
        $templateContent = str_replace(
            ['{bundle}', '{bundleVariable}'],
            [$bundle, lcfirst($bundle)],
            $templateContent
        );

        return $templateContent;
    }

    /**
     * @param string $bundle
     * @param string $templateContent
     * @param string $fileName
     * @param bool $overwrite
     *
     * @return void
     */
    protected function saveFile($bundle, $templateContent, $fileName, $overwrite = false)
    {
        $path = $this->bundleRootDirectory . $bundle . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir($path, 0770, true);
        }

        $filesystem = new Filesystem();
        $filePath = $path . $fileName;

        if (!is_file($filePath) || $overwrite) {
            $filesystem->dumpFile($filePath, $templateContent);
        }
    }

}
