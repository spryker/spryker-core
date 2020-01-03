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
    protected const TEMPLATE_INTERFACE = 'interface';
    protected const TEMPLATE_BRIDGE = 'bridge';
    protected const TEMPLATE_INTERFACE_METHOD = 'interface_method';
    protected const TEMPLATE_BRIDGE_METHOD = 'bridge_method';

    protected const DEFAULT_VENDOR = 'Spryker';
    protected const DEFAULT_TO_TYPE = 'Facade';

    protected const APPLICATION_LAYER_MAP = [
        'Facade' => 'Zed',
        'QueryContainer' => 'Zed',
    ];

    protected const MODULE_LAYER_MAP = [
        'Facade' => 'Business',
        'QueryContainer' => 'Persistence',
    ];

    protected const FUNCTION_RETURN = 'return ';

    protected const NULLABLE_RETURN_TYPE_HINT = ': ?';
    protected const NON_NULLABLE_RETURN_TYPE_HINT = ': ';

    protected const TYPE_HINT = 'type_hint';
    protected const FQCN = 'fqcn';

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
    public function build($bundle, $toBundle, $methods): void
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
    protected function createInterface(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): void
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
    protected function createBridge(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): void
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
    protected function getInterfaceTemplateContent(): string
    {
        return $this->getTemplateContent(static::TEMPLATE_INTERFACE);
    }

    /**
     * @return string
     */
    protected function getInterfaceMethodTemplateContent(): string
    {
        return $this->getTemplateContent(static::TEMPLATE_INTERFACE_METHOD);
    }

    /**
     * @return string
     */
    protected function getBridgeMethodTemplateContent(): string
    {
        return $this->getTemplateContent(static::TEMPLATE_BRIDGE_METHOD);
    }

    /**
     * @return string
     */
    protected function getBridgeTemplateContent(): string
    {
        return $this->getTemplateContent(static::TEMPLATE_BRIDGE);
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getTemplateContent(string $templateName): string
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
    protected function replacePlaceHolder(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, string $templateContent): string
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
    protected function saveFile(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, string $templateContent, string $fileName): void
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
    protected function getPathToDependencyFiles(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): string
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
    protected function getBridgeBuilderData(string $source, string $target, array $methods): BridgeBuilderDataTransfer
    {
        [$vendor, $module, $type] = $this->interpretInputParameter($source);
        [$toVendor, $toModule, $toType] = $this->interpretInputParameter($target);

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
    protected function interpretInputParameter($subject): array
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
    protected function getApplicationLayer($type): string
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
    protected function getModuleLayer($type): string
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
    protected function resolveModulePath(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): string
    {
        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($bridgeBuilderDataTransfer->getVendor());
        if ($pathToInternalNamespace) {
            return $pathToInternalNamespace . $bridgeBuilderDataTransfer->getModule();
        }

        $vendorDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getVendor());
        $moduleDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getModule());

        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_VENDOR_DIR,
            $vendorDirectory,
            $moduleDirectory,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function resolveTargetModulePath(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): string
    {
        $pathToInternalNamespace = $this->config->getPathToInternalNamespace($bridgeBuilderDataTransfer->getToVendor());
        if ($pathToInternalNamespace) {
            return $pathToInternalNamespace . $bridgeBuilderDataTransfer->getToModule();
        }

        $vendorDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getToVendor());
        $moduleDirectory = $this->normalizeNameForSplit($bridgeBuilderDataTransfer->getToModule());

        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_VENDOR_DIR,
            $vendorDirectory,
            $moduleDirectory,
        ]);
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeNameForSplit($module): string
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
    protected function checkIfBridgeTargetExists(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): bool
    {
        return is_file($this->getBridgeTarget($bridgeBuilderDataTransfer) . '.php');
    }

    /**
     * @param \Generated\Shared\Transfer\BridgeBuilderDataTransfer $bridgeBuilderDataTransfer
     *
     * @return string
     */
    protected function getBridgeTarget(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer): string
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
    protected function addMethodsToBridge(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, string $templateContent): string
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
    protected function addMethodsToInterface(BridgeBuilderDataTransfer $bridgeBuilderDataTransfer, string $templateContent): string
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
    protected function addMethodsToTemplate(ReflectionClass $reflectionClass, array $methodNames, string $methodTemplate, string $templateContent): string
    {
        $methods = '';
        $useStatements = [];

        foreach (array_unique($methodNames) as $methodName) {
            if (empty($methodName)) {
                continue;
            }
            $method = $reflectionClass->getMethod($methodName);

            $docComment = $this->cleanMethodDocBlock($method->getDocComment());
            $methodReturnType = $this->getMethodReturnTypeFromDocComment($docComment);

            $returnStatementReplacement = static::FUNCTION_RETURN;
            $returnMethodTypeHint = '';
            if ((string)$method->getReturnType()) {
                $returnMethodTypeHint = $this->getMethodTypeHintForFunction($methodReturnType);
            }

            if ($methodReturnType === 'void') {
                $returnStatementReplacement = '';
            }

            $useStatements = array_merge($useStatements, $this->getParameterTypes($method));

            if (is_array($returnMethodTypeHint)) {
                $useStatements = array_merge($useStatements, [$returnMethodTypeHint[static::FQCN] => true]);
                $returnMethodTypeHint = $returnMethodTypeHint[static::TYPE_HINT];
            }

            $replacements = [
                '{docBlock}' => $docComment,
                '{methodName}' => $methodName,
                '{returnStatement}' => $returnStatementReplacement,
                '{returnMethodTypeHint}' => $returnMethodTypeHint,
                '{parameters}' => $this->getParameters($method),
                '{parametersWithoutTypes}' => $this->getParameterNames($method),
            ];

            $methods .= str_replace(
                array_keys($replacements),
                array_values($replacements),
                $methodTemplate
            ) . PHP_EOL . PHP_EOL . str_repeat(' ', 4);
        }

        $useStatements = array_keys($useStatements);
        sort($useStatements);
        $useStatements = array_reduce($useStatements, function ($prevUseStatement, $useStatement) {
            return $prevUseStatement . PHP_EOL . 'use ' . $useStatement . ';';
        }, '');

        return str_replace(
            [
                '{methods}',
                '{useStatements}',
            ],
            [
                rtrim($methods, PHP_EOL . PHP_EOL . str_repeat(' ', 4)),
                $useStatements,
            ],
            $templateContent
        );
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return string
     */
    protected function getParameters(ReflectionMethod $method): string
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

                if (!is_array($parameter->getDefaultValue())) {
                    $finalOutput .= $parameter->getDefaultValue();
                }

                if (is_array($parameter->getDefaultValue())) {
                    $finalOutput .= '[]';
                }
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
    protected function getClassNameFromFqcn($fqcn): string
    {
        $arr = explode('\\', $fqcn);

        return end($arr);
    }

    /**
     * @param string $docComment
     *
     * @return string
     */
    protected function cleanMethodDocBlock($docComment): string
    {
        $docCommentWithoutExtras = preg_replace('/.+?(?=@param|@return)/ms', '/**' . PHP_EOL . "\t * ", $docComment, 1);

        return str_replace("\t", str_repeat(' ', 4), $docCommentWithoutExtras);
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return array
     */
    protected function getParameterTypes(ReflectionMethod $method): array
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
    protected function getParameterNames(ReflectionMethod $method): string
    {
        $parameters = '';
        foreach ($method->getParameters() as $parameter) {
            $parameters .= '$' . $parameter->getName() . ', ';
        }

        return rtrim($parameters, ', ');
    }

    /**
     * @param string $docComment
     *
     * @return string
     */
    protected function getMethodReturnTypeFromDocComment(string $docComment): string
    {
        preg_match('/@return (.+)/', $docComment, $returnType);

        if (!$returnType) {
            return '';
        }

        return $returnType[1];
    }

    /**
     * @param string $methodReturnType
     *
     * @return array|string
     */
    protected function getMethodTypeHintForFunction(string $methodReturnType)
    {
        $methodReturnParts = explode('|', $methodReturnType);
        $numberOfReturnParts = count($methodReturnParts);

        if ($numberOfReturnParts === 1) {
            if (strpos($methodReturnType, '\\') !== false) {
                $methodTypeHintArray = explode('\\', $methodReturnType);

                return [
                    static::TYPE_HINT => static::NON_NULLABLE_RETURN_TYPE_HINT . end($methodTypeHintArray),
                    static::FQCN => ltrim($methodReturnType, '\\'),
                ];
            }

            return static::NON_NULLABLE_RETURN_TYPE_HINT . $this->arrayReturnTypeFix($methodReturnType);
        }

        $nullReturnTypeIndex = array_search('null', $methodReturnParts, true);

        if ($nullReturnTypeIndex === false && $numberOfReturnParts > 1) {
            return '';
        }

        if ($nullReturnTypeIndex !== false && $numberOfReturnParts > 2) {
            return '';
        }

        $methodTypeHint = $methodReturnParts[0];

        if ($nullReturnTypeIndex === 0) {
            $methodTypeHint = $methodReturnParts[1];
        }

        if (strpos($methodTypeHint, '\\') !== false) {
            $methodTypeHintArray = explode('\\', $methodTypeHint);

            return [
                static::TYPE_HINT => static::NULLABLE_RETURN_TYPE_HINT . end($methodTypeHintArray),
                static::FQCN => ltrim($methodTypeHint, '\\'),
            ];
        }

        return static::NULLABLE_RETURN_TYPE_HINT . $this->arrayReturnTypeFix($methodTypeHint);
    }

    /**
     * @param string $returnType
     *
     * @return string
     */
    protected function arrayReturnTypeFix(string $returnType): string
    {
        return (strpos($returnType, '[]') === false) ? $returnType : 'array';
    }
}
