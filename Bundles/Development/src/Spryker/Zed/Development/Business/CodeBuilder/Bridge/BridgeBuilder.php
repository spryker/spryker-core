<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeBuilder\Bridge;

use Generated\Shared\Transfer\BridgeBuilderDataTransfer;
use ReflectionClass;
use ReflectionMethod;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BridgeBuilder
{
    const TEMPLATE_INTERFACE = 'interface';
    const TEMPLATE_BRIDGE = 'bridge';
    const TEMPLATE_INTERFACE_METHOD = 'interface_method';
    const TEMPLATE_BRIDGE_METHOD = 'bridge_method';

    const DEFAULT_VENDOR = 'Spryker';
    const DEFAULT_TO_TYPE = 'Facade';

    const APPLICATION_LAYER_MAP = [
        'Facade' => 'Zed',
        'QueryContainer' => 'Zed',
    ];

    const MODULE_LAYER_MAP = [
        'Facade' => 'Business',
        'QueryContainer' => 'Persistence',
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
     * @param array $methods
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function build($bundle, $toBundle, $methods)
    {
        $bridgeBuilderDataTransfer = $this->getBridgeBuilderData($bundle, $toBundle, $methods);
        if (!$this->checkIfBridgeTargetExists($bridgeBuilderDataTransfer)) {
            throw new InvalidArgumentException('Trying to create bridge to target that does not exist');
        }

        $this->createInterface($bridgeBuilderDataTransfer);
        $this->createBridge($bridgeBuilderDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return void
     */
    protected function createInterface(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        $fileName = $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Interface.php';
        $interfaceFilePath = $this->getPathToDependencyFiles($bridgeBuilderDataTransfer) . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($interfaceFilePath)) {
            $existingInterface = new ReflectionClass(
                $bridgeBuilderDataTransfer->getVendor() . '\\' .
                $bridgeBuilderDataTransfer->getApplication() . '\\' .
                $bridgeBuilderDataTransfer->getModule() . '\\' .
                'Dependency\\' .
                $bridgeBuilderDataTransfer->getToType() . '\\' .
                $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Interface'
            );

            foreach ($existingInterface->getMethods() as $method) {
                $bridgeBuilderDataTransfer->addMethods($method->getName());
            }
        }

        $templateContent = $this->getInterfaceTemplateContent();
        $templateContent = $this->addMethodsToInterface($bridgeBuilderDataTransfer, $templateContent);
        $templateContent = $this->replacePlaceHolder($bridgeBuilderDataTransfer, $templateContent);

        $this->saveFile($bridgeBuilderDataTransfer, $templateContent, $fileName);
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return void
     */
    protected function createBridge(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        $fileName = $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Bridge.php';
        $bridgeFilePath = $this->getPathToDependencyFiles($bridgeBuilderDataTransfer) . DIRECTORY_SEPARATOR . $fileName;

        if (is_file($bridgeFilePath)) {
            $existingBridge = new ReflectionClass(
                $bridgeBuilderDataTransfer->getVendor() . '\\' .
                $bridgeBuilderDataTransfer->getApplication() . '\\' .
                $bridgeBuilderDataTransfer->getModule() . '\\' .
                'Dependency\\' .
                $bridgeBuilderDataTransfer->getToType() . '\\' .
                $bridgeBuilderDataTransfer->getModule() . 'To' . $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Bridge'
            );

            foreach ($existingBridge->getMethods() as $method) {
                if (!$method->isConstructor()) {
                    $bridgeBuilderDataTransfer->addMethods($method->getName());
                }
            }
        }

        $templateContent = $this->getBridgeTemplateContent();
        $templateContent = $this->addMethodsToBridge($bridgeBuilderDataTransfer, $templateContent);
        $templateContent = $this->replacePlaceHolder($bridgeBuilderDataTransfer, $templateContent);

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
    protected function getInterfaceMethodTemplateContent()
    {
        return $this->getTemplateContent(static::TEMPLATE_INTERFACE_METHOD);
    }

    /**
     * @return string
     */
    protected function getBridgeMethodTemplateContent()
    {
        return $this->getTemplateContent(static::TEMPLATE_BRIDGE_METHOD);
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

            '{toModuleLayer}' => '',
            '{toModuleVariable}' => lcfirst($bridgeBuilderDataTransfer->getToModule()),
        ];

        if ($this->getModuleLayer($bridgeBuilderDataTransfer->getToType())) {
            $replacements['{toModuleLayer}'] = '\\' . $this->getModuleLayer($bridgeBuilderDataTransfer->getToType());
        }

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

        $filesystem->dumpFile($filePath, $templateContent);
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
            $bridgeBuilderDataTransfer->getApplication(),
            $bridgeBuilderDataTransfer->getModule(),
            'Dependency',
            $bridgeBuilderDataTransfer->getToType(),
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    /**
     * @param string $source
     * @param string $target
     * @param array $methods
     *
     * @return \Generated\Shared\Transfer\BridgeBuilderDataTransfer
     */
    protected function getBridgeBuilderData($source, $target, array $methods)
    {
        list($vendor, $module, $type) = $this->interpretInputParameter($source);
        list($toVendor, $toModule, $toType) = $this->interpretInputParameter($target);

        $bridgeBuilderDataTransfer = new BridgeBuilderDataTransfer();
        $bridgeBuilderDataTransfer
            ->setVendor($vendor)
            ->setModule($module)
            ->setModuleLayer($this->getModuleLayer($type))
            ->setType($type)
            ->setApplication($this->getApplicationLayer($type))
            ->setToVendor($toVendor)
            ->setToModule($toModule)
            ->setToModuleLayer($this->getModuleLayer($toType))
            ->setToType($toType)
            ->setToApplication($this->getApplicationLayer($toType))
            ->setMethods($methods);

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
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function resolveTargetModulePath(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        switch ($bridgeBuilderDataTransfer->getToVendor()) {
            case 'Spryker':
                return $this->config->getPathToCore() . $bridgeBuilderDataTransfer->getToModule();

            case 'SprykerShop':
                return $this->config->getPathToShop() . $bridgeBuilderDataTransfer->getToModule();

            default:
                $vendorDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getToVendor());
                $moduleDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getToModule());

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

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return bool
     */
    protected function checkIfBridgeTargetExists(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        return is_file($this->getBridgeTarget($bridgeBuilderDataTransfer) . '.php');
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function getBridgeTarget(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer)
    {
        $pathParts = [
            $this->resolveTargetModulePath($bridgeBuilderDataTransfer),
            'src',
            $bridgeBuilderDataTransfer->getToVendor(),
            $bridgeBuilderDataTransfer->getToApplication(),
            $bridgeBuilderDataTransfer->getToModule(),
            $bridgeBuilderDataTransfer->getToModuleLayer(),
            $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType(),
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     * @param string $templateContent
     *
     * @return string
     */
    protected function addMethodsToBridge(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, $templateContent)
    {
        $path =
            $bridgeBuilderDataTransfer->getToVendor() . '\\' .
            $bridgeBuilderDataTransfer->getToApplication() . '\\' .
            $bridgeBuilderDataTransfer->getToModule() . '\\';

        if ($bridgeBuilderDataTransfer->getToModuleLayer()) {
            $path .= $bridgeBuilderDataTransfer->getToModuleLayer() . '\\';
        }

        $path .= $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType();

        $targetBridgeClass = new ReflectionClass($path);

        return $this->addMethodsToTemplate(
            $targetBridgeClass,
            $bridgeBuilderDataTransfer->getMethods(),
            $this->getBridgeMethodTemplateContent(),
            $templateContent
        );
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     * @param string $templateContent
     *
     * @return string
     */
    protected function addMethodsToInterface(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, $templateContent)
    {
        $path =
            $bridgeBuilderDataTransfer->getToVendor() . '\\' .
            $bridgeBuilderDataTransfer->getToApplication() . '\\' .
            $bridgeBuilderDataTransfer->getToModule() . '\\';

        if ($bridgeBuilderDataTransfer->getToModuleLayer()) {
            $path .= $bridgeBuilderDataTransfer->getToModuleLayer() . '\\';
        }

        $path .= $bridgeBuilderDataTransfer->getToModule() . $bridgeBuilderDataTransfer->getToType() . 'Interface';

        $targetBridgeInterface = new ReflectionClass($path);

        return $this->addMethodsToTemplate(
            $targetBridgeInterface,
            $bridgeBuilderDataTransfer->getMethods(),
            $this->getInterfaceMethodTemplateContent(),
            $templateContent
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param array $methodNames
     * @param string $methodTemplate
     * @param string $templateContent
     *
     * @return string
     */
    protected function addMethodsToTemplate(ReflectionClass $reflectionClass, $methodNames, $methodTemplate, $templateContent)
    {
        $methods = '';
        $useStatements = [];

        foreach (array_unique($methodNames) as $methodName) {
            $method = $reflectionClass->getMethod($methodName);
            $docComment = $this->cleanMethodDocBlock($method->getDocComment());

            $useStatements = array_merge(
                $useStatements,
                $this->getParameterTypes($method)
            );

            $replacements = [
                '{docBlock}' => $docComment,
                '{methodName}' => $methodName,
                '{parameters}' => $this->getParameters($method),
                '{parametersWithoutTypes}' => $this->getParameterNames($method),
            ];

            $methods .=
                str_replace(
                    array_keys($replacements),
                    array_values($replacements),
                    $methodTemplate
                )
                . PHP_EOL . PHP_EOL . "\t";
        }

        $useStatements = array_keys($useStatements);
        $useStatements = array_reduce($useStatements, function ($prevUseStatement, $useStatement) {
            return $prevUseStatement . PHP_EOL . 'use ' . $useStatement . ';';
        }, '');

        $templateContent = str_replace('{methods}', rtrim($methods, PHP_EOL . PHP_EOL . "\t"), $templateContent);
        return str_replace('{useStatements}', $useStatements, $templateContent);
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return string
     */
    protected function getParameters(ReflectionMethod $method)
    {
        $finalOutput = '';

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType()) {
                $finalOutput .= $this->getClassNameFromFqcn($parameter->getType()->getName()) . ' ';
            }

            $finalOutput .= '$' . $parameter->getName();

            if ($parameter->isDefaultValueAvailable()) {
                $finalOutput .= ' = ';

                if ($parameter->getDefaultValue() === null) {
                    $finalOutput .= 'null';
                }

                $finalOutput .= $parameter->getDefaultValue();
            }

            $finalOutput .= ', ';
        }

        return rtrim($finalOutput, ', ');
    }

    /**
     * @param string $fqcn
     *
     * @return string
     */
    protected function getClassNameFromFqcn($fqcn)
    {
        $arr = explode('\\', $fqcn);
        return end($arr);
    }

    /**
     * @param string $docComment
     *
     * @return string
     */
    protected function cleanMethodDocBlock($docComment)
    {
        return preg_replace('/.+?(?=@param)/ms', '/**' . PHP_EOL . "\t * ", $docComment, 1);
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return array
     */
    protected function getParameterTypes(ReflectionMethod $method)
    {
        $parameterTypes = [];
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType() && !$parameter->getType()->isBuiltin()) {
                $type = $parameter->getType()->getName();
                if (isset($parameterTypes[$type])) {
                    continue;
                }

                $parameterTypes[$type] = true;
            }
        }

        return $parameterTypes;
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return string
     */
    protected function getParameterNames(ReflectionMethod $method)
    {
        $parameters = '';
        foreach ($method->getParameters() as $parameter) {
            $parameters .= '$' . $parameter->getName() . ', ';
        }

        return rtrim($parameters, ', ');
    }
}
