<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeBuilder\Bridge;

use Generated\Shared\Transfer\BridgeBuilderDataTransfer;
use InvalidArgumentException;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Filesystem\Filesystem;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BridgeBuilder
{
    const TEMPLATE_INTERFACE = 'interface';
    const TEMPLATE_BRIDGE = 'bridge';

    const DEFAULT_VENDOR = 'Spryker';
    const DEFAULT_TO_TYPE = 'Facade';
    const APPLICATION_LAYER_MAP = [
        'Facade' => 'Zed',
        'QueryContainer' => 'Zed',
    ];
    const MODULE_LAYER_MAP = [
        'Facade' => '\\Business',
        'QueryContainer' => '\\Persistence',
    ];

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $bundle
     * @param string $toBundle
     *
     * @return void
     */
    public function build($bundle, $toBundle)
    {
        $this->createInterface($bundle, $toBundle);
        $this->createBridge($bundle, $toBundle);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return void
     */
    protected function createInterface($source, $target)
    {
        $bridgeBuilderDataTransfer = $this->getBridgeBuilderData($source, $target);

        $templateContent = $this->getInterfaceTemplateContent();
        $templateContent = $this->replacePlaceHolder($bridgeBuilderDataTransfer, $templateContent);

        $fileName = $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Interface.php';

        $this->saveFile($bridgeBuilderDataTransfer, $templateContent, $fileName);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return void
     */
    protected function createBridge($source, $target)
    {
        $bridgeBuilderDataTransfer = $this->getBridgeBuilderData($source, $target);

        $templateContent = $this->getBridgeTemplateContent();
        $templateContent = $this->replacePlaceHolder($bridgeBuilderDataTransfer, $templateContent);

        $fileName = $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Bridge.php';

        $this->saveFile($bridgeBuilderDataTransfer, $templateContent, $fileName);
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
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     * @param string $templateContent
     *
     * @return string
     */
    protected function replacePlaceHolder(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, $templateContent)
    {
        $replacements = [
            '{vendor}' => $bridgeBuilderDataTransfer->getVendor(),
            '{application}' => $bridgeBuilderDataTransfer->getApplication(),
            '{module}' => $bridgeBuilderDataTransfer->getModule(),
            '{type}' => $bridgeBuilderDataTransfer->getType(),

            '{toVendor}' => $bridgeBuilderDataTransfer->getToVendor(),
            '{toApplication}' => $bridgeBuilderDataTransfer->getToApplication(),
            '{toModule}' => $bridgeBuilderDataTransfer->getToModule(),
            '{toType}' => $bridgeBuilderDataTransfer->getToType(),

            '{toModuleLayer}' => $this->getModuleLayer($bridgeBuilderDataTransfer->getToType()),
            '{toModuleVariable}' => lcfirst($bridgeBuilderDataTransfer->getToModule()),
        ];

        $templateContent = str_replace(array_keys($replacements), array_values($replacements), $templateContent);

        return $templateContent;
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     * @param string $templateContent
     * @param string $fileName
     *
     * @return void
     */
    protected function saveFile(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, $templateContent, $fileName)
    {
        $path = $this->getPathToDependencyFiles($bridgeBuilderDataTransfer);

        $filesystem = new Filesystem();
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;

        if (!is_file($filePath)) {
            $filesystem->dumpFile($filePath, $templateContent);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function getPathToDependencyFiles(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        $pathParts = [
            $this->resolveModulePath($bridgeBuilderDataTransfer),
            'src',
            $bridgeBuilderDataTransfer->getVendor(),
            $bridgeBuilderDataTransfer->getType(),
            $bridgeBuilderDataTransfer->getModule(),
            'Dependency',
            $bridgeBuilderDataTransfer->getToType(),
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Generated\Shared\Transfer\BridgeBuilderDataTransfer
     */
    protected function getBridgeBuilderData($source, $target)
    {
        list($vendor, $module, $type) = $this->interpretInputParameter($source);
        list($toVendor, $toModule, $toType) = $this->interpretInputParameter($target);

        $bridgeBuilderDataTransfer = new BridgeBuilderDataTransfer();
        $bridgeBuilderDataTransfer
            ->setVendor($vendor)
            ->setModule($module)
            ->setType($type)
            ->setApplication($this->getApplicationLayer($type))
            ->setToVendor($toVendor)
            ->setToModule($toModule)
            ->setToType($toType)
            ->setToApplication($this->getApplicationLayer($toType));

        return $bridgeBuilderDataTransfer;
    }

    /**
     * @param string $subject
     *
     * @throws \InvalidArgumentException
     *
     * @return array of [VendorName, ModuleName, Type]
     */
    protected function interpretInputParameter($subject)
    {
        if (preg_match('/^(\w+)$/', $subject, $matches)) {
            return [
                static::DEFAULT_VENDOR,
                $matches[1],
                static::DEFAULT_TO_TYPE,
            ];
        }

        if (preg_match('/^(\w+)\.(\w+)$/', $subject, $matches)) {
            return [
                static::DEFAULT_VENDOR,
                $matches[1],
                $matches[2],
            ];
        }

        if (preg_match('/^(\w+).(\w+).(\w+)$/', $subject, $matches)) {
            return [
                $matches[1],
                $matches[2],
                $matches[3],
            ];
        }

        throw new InvalidArgumentException(sprintf(
            'Invalid input parameter "%s", accepted format is "[VendorName.]ModuleName[.BridgeType]".',
            $subject
        ));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getApplicationLayer($type)
    {
        if (isset(static::APPLICATION_LAYER_MAP[$type])) {
            return static::APPLICATION_LAYER_MAP[$type];
        }

        return $type;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getModuleLayer($type)
    {
        if (isset(static::MODULE_LAYER_MAP[$type])) {
            return static::MODULE_LAYER_MAP[$type];
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function resolveModulePath(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        switch ($bridgeBuilderDataTransfer->getVendor()) {
            case 'Spryker':
                return $this->config->getPathToCore() . $bridgeBuilderDataTransfer->getModule();

            case 'SprykerShop':
                return $this->config->getPathToShop() . $bridgeBuilderDataTransfer->getModule();

            default:
                $vendorDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getVendor());
                $moduleDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getModule());

                return implode(DIRECTORY_SEPARATOR, [
                    APPLICATION_VENDOR_DIR,
                    $vendorDirectory,
                    $moduleDirectory,
                ]);
        }
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeNameForSplit($module)
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new UnderscoreToCamelCase())
            ->attach(new CamelCaseToDash());

        return strtolower($filterChain->filter($module));
    }
}
