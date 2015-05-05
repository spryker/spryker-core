<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Config\Definition\Exception\Exception;

class ClassGenerator
{

    const TWIG_TEMPLATES_LOCATION = '/Templates/';

    /**
     * @var ClassDefinition
     */
    protected $classDefinition;

    /**
     * @var string|null
     */
    protected $namespace = null;

    /**
     * @var null
     */
    protected $targetFolder = null;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $externalResourcesToUse = [];

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @var string $properties
     */
    protected $properties = '';

    public function __construct()
    {
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new \Twig_Environment($loader, []);
        $this->twig->addExtension(new TransferTwigExtensions());
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * set path where generated classes will be written
     * @param string $path
     */
    public function setTargetFolder($path)
    {
        $this->targetFolder = $path;
    }

    /**
     * @return string
     */
    public function getTargetFolder()
    {
        return $this->targetFolder;
    }

    /**
     * @param ClassDefinition $definition
     * @return string
     */
    public function generateClass(ClassDefinition $definition)
    {
        $this->classDefinition = $definition;
        $this->generateExternalResourcesToUse();

        $this->buildTwigData();

        $translations = $this->getTranslations();

        return $this->twig->render('class.php.twig', $translations);
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @return array
     */
    public function getExternalResourcesToUse()
    {
        return $this->externalResourcesToUse;
    }

    /**
     * generate array that will be used in twig template to generate class file
     */
    protected function buildTwigData()
    {
        $this->translations = [
            'namespace' => $this->getNamespace(),
            'className' => $this->classDefinition->getClassName(),
            'interfacesToImplement' => $this->getInterfacesToImplement(),
            'properties' => $this->generatePropertiesDeclarations(),
            'settersAndGetters' => $this->generateSettersAndGetters(),
            'useExternal' => $this->getExternalResourcesToUse(),
            'needsConstructor' => $this->classDefinition->getNeedsConstructor(),
        ];
    }

    /**
     * @return $this
     */
    public function generateExternalResourcesToUse()
    {
        $interfaces = $this->classDefinition->getInterfaces();
        foreach ($interfaces as $int) {
            if (is_array($int)) {
                foreach ($int as $interfaceItem) {
                    $this->addExternalResource($interfaceItem);
                }
            } else {
                $this->addExternalResource($int);
            }
        }

        $this->addExternalResource($this->classDefinition->getUses());

        return $this;
    }

    /**
     * if an array is passed as a parameter, then it will loop each member and add it
     *
     * @param array|string $resourceNamespace
     */
    protected function addExternalResource($resourceNamespace)
    {
        if (is_array($resourceNamespace)) {
            foreach ($resourceNamespace as $item) {
                $this->addExternalResource($item);
            }
        } else {
            if ($resourceNamespace === 'SprykerEngine\Shared\Transfer\AbstractTransfer') {
                return;
            }
            $resourceNamespace = $this->appendInterfaceName($resourceNamespace);
            $resourceNamespace = strtr($resourceNamespace, ['\\\\' => '\\']);
            if (!in_array($resourceNamespace, $this->externalResourcesToUse)
                && strpos($resourceNamespace, '\\') !== false
            ) {
                $this->externalResourcesToUse[] = $resourceNamespace;
            }
        }
    }

    /**
     * @return string
     */
    public function getInterfacesToImplement()
    {
        $interfaces = $this->classDefinition->getInterfaces();

        $interfacesNames = [];
        foreach ($interfaces as $int) {
            if (is_array($int)) {
                foreach ($int as $interfaceItem) {
                    $this->addExternalResource($interfaceItem);
                    $interfacesNames[] = $this->getNamespaceBaseName($interfaceItem);
                }
            } else {
                $this->addExternalResource($int);
                $interfacesNames[] = $this->getNamespaceBaseName($int);
            }
        }

        return implode(', ', $interfacesNames);
    }

    /**
     * returns the name of a class from namespace
     *
     * @param string $name
     * @return string
     */
    protected function getNamespaceBaseName($name)
    {
        $temporary = explode('\\', $name);

        return end($temporary);
    }

    /**
     * returns a list of properties that new class should have
     *
     * @return array
     */
    public function generatePropertiesDeclarations()
    {
        $declarations = [];
        $properties = $this->classDefinition->getProperties();

        foreach ($properties as $props) {
            $declarations[] = [
                'parameterDataType' => $this->getParameterDataType($props['type']),
                'propertyName' => $this->getPassedParameter($props),
                'defaultValue' => $this->getDefaultValue($props),
            ];
        }

        return $declarations;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isSpecialProperty($type)
    {
        return preg_match('/\[\]/', $type);
    }

    /**
     * @param array $properties
     * @return null|string
     */
    protected function getDefaultValue(array $properties)
    {
        if (preg_match('/(array|\[\])/', $properties['type'])) {
            return '[]';
        }

        return 'null';
    }

    /**
     * @param $dataArray
     * @return string
     */
    public function getPropertyName($dataArray)
    {
        return ucfirst($this->getPassedParameter($dataArray));
    }

    /**
     * @param string $type
     * @param bool $isForDocumentation
     * @return mixed|string
     */
    public function getParameterDataType($type, $isForDocumentation = false)
    {
        if ('array' === $type) {
            return ClassDefinition::TYPE_ARRAY;
        }
        if ('bool' === $type || 'boolean' === $type) {
            return ClassDefinition::TYPE_BOOLEAN;
        }

        if (preg_match('/^int/', $type)) {
            return ClassDefinition::TYPE_INTEGER;
        }

        if (!preg_match('/(string|integer|int)/', $type) && !$isForDocumentation) {
            $this->addExternalResource($type);
            $resourceType = $this->getNamespaceBaseName($type);

            return $this->appendInterfaceName($resourceType);
        }

        return ClassDefinition::TYPE_STRING;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getPassedParameter(array $data)
    {
        if (!isset($data['name'])) {
            throw new Exception('name not found in ' . var_export($data, true));
        }

        return $data['name'];
    }

    /**
     * @return array
     */
    protected function generateSettersAndGetters()
    {
        $properties = $this->classDefinition->getProperties();

        $settersAndGetters = [];

        foreach ($properties as $props) {
            $settersAndGetters[] = [
                'propertyName' => $this->getPropertyName($props),
                'passedParameter' => $this->getPassedParameter($props),
                'parameterDataType' => $this->getParameterDataType($props['type']),
                'is_special' => $this->isSpecialProperty($props['type']),
            ];
        }

        return $settersAndGetters;
    }

    /**
     * @param string $resourceNamespace
     * @return string
     */
    protected function appendInterfaceName($resourceNamespace)
    {
        if (!preg_match('/Interface$/', $resourceNamespace)) {
            if (interface_exists($resourceNamespace . 'Interface')) {
                return $resourceNamespace . 'Interface';
            }
        }

        return $resourceNamespace;
    }
}
