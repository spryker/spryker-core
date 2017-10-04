<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeBuilder\Module;

use Symfony\Component\Filesystem\Filesystem;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToDash;

class ModuleBuilder
{

    const TEMPLATE_INTERFACE = 'interface';
    const TEMPLATE_BRIDGE = 'bridge';

    /**
     * @var string
     */
    protected $moduleRootDirectory;

    /**
     * List of files to generate in each module.
     *
     * The codecept alias is required to not trigger codeception autoloading.
     *
     * @var array
     */
    protected $files = [
        '.gitattributes',
        '.gitignore',
        '.coveralls.yml',
        '.travis.yml',
        'codecept.yml' => 'codeception.yml',
        'composer.json',
        'CHANGELOG.md',
        'README.md',
        'LICENSE'
    ];

    /**
     * @param string $moduleRootDirectory
     */
    public function __construct($moduleRootDirectory)
    {
        $this->moduleRootDirectory = $moduleRootDirectory;
    }

    /**
     * @param string $module
     * @param array $options
     *
     * @return void
     */
    public function build($module, array $options)
    {
        if ($module !== 'all') {
            $module = $this->getUnderscoreToCamelCaseFilter()->filter($module);
            $modules = (array)$module;
        } else {
            $modules = $this->getModuleNames();
        }

        foreach ($modules as $module) {
            $this->createOrUpdateModule($module, $options);
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
    protected function getModuleNames()
    {
        $moduleDirectories = glob($this->moduleRootDirectory . '*');

        $modules = [];
        foreach ($moduleDirectories as $moduleDirectory) {
            $modules[] = pathinfo($moduleDirectory, PATHINFO_BASENAME);
        }

        return $modules;
    }

    /**
     * @param string $module
     * @param array $options
     *
     * @return void
     */
    protected function createOrUpdateModule($module, $options)
    {
        foreach ($this->files as $alias => $file) {
            $source = $file;
            if (is_string($alias)) {
                $source = $alias;
            }

            if (!empty($options['file']) && $file !== $options['file']) {
                continue;
            }

            $templateContent = $this->getTemplateContent($source);

            $templateContent = $this->replacePlaceHolder($module, $templateContent);

            $this->saveFile($module, $templateContent, $file, $options['force']);
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
     * @param string $module
     * @param string $templateContent
     *
     * @return string
     */
    protected function replacePlaceHolder($module, $templateContent)
    {
        $templateContent = str_replace(
            ['{module}', '{moduleVariable}', '{moduleDashed}'],
            [$module, lcfirst($module), $this->camelCaseToDash($module)],
            $templateContent
        );

        return $templateContent;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function camelCaseToDash($module)
    {
        $filter = new CamelCaseToDash();

        $module = strtolower($filter->filter($module));

        return $module;
    }

    /**
     * @param string $module
     * @param string $templateContent
     * @param string $fileName
     * @param bool $overwrite
     *
     * @return void
     */
    protected function saveFile($module, $templateContent, $fileName, $overwrite = false)
    {
        $path = $this->moduleRootDirectory . $module . DIRECTORY_SEPARATOR;
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
