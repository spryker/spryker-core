<?php

namespace SprykerFeature\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Config\Definition\Exception\Exception;

class ClassGenerator
{
    const TWIG_TEMPLATES_LOCATION = '/Templates/';

    protected $classDefinition;

    protected $targetFolder = null;

    protected $twig;

    protected $externalResourcesToUse = [];

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

    public function setTargetFolder($path)
    {
        $this->targetFolder = $path;
    }

    public function generateClass(ClassDefinition $definition)
    {
        $this->classDefinition = $definition;
        $this->generateExternalResourcesToUse();

        $this->buildTwigData();

        $translations = $this->getTranslations();

        return $this->twig->render('class.php.twig', $translations);
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function getExternalResourcesToUse()
    {
        return $this->externalResourcesToUse;
    }

    protected function buildTwigData()
    {
        $this->translations = array(
            'namespace'     => 'Generated',
            'className'     => $this->classDefinition->getClassName(),
            'interfacesToImplement' => $this->getInterfacesToImplement(),
            'properties'    => $this->generatePropertiesDeclarations(),
            'settersAndGetters' => $this->generateSettersAndGetters(),
            'useExternal'   => $this->getExternalResourcesToUse(),
        );
    }

    public function generateExternalResourcesToUse()
    {
        $interfaces = $this->classDefinition->getInterfaces();
        foreach ($interfaces as $int) {
            $this->addExternalResource($int);
        }

        return $this;
    }

    protected function addExternalResource($resourceNamespace)
    {
        if ( ! in_array($resourceNamespace, $this->externalResourcesToUse) ) {
            $this->externalResourcesToUse[] = $resourceNamespace;
        }
    }

    public function getInterfacesToImplement()
    {
        $interfaces = $this->classDefinition->getInterfaces();

        $interfacesNames = [];
        foreach ($interfaces as $int) {
            $this->addExternalResource($int);
            $interfacesNames[] = $this->getNamespaceBaseName($int);
        }
        return implode(', ', $interfacesNames);
    }

    protected function getNamespaceBaseName($name)
    {
        $temporary = explode('\\', $name);

        return end($temporary);
    }

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

    protected function getDefaultValue(array $properties)
    {
        if ( preg_match('/(array|\[\])/', $properties['type']) ) {
            return 'array()';
        }

        if ( ! empty($properties['default']) ) {
            return $properties['default'];
        }

        return null;
    }

    public function getPropertyName($dataArray)
    {
        return ucfirst($this->getPassedParameter($dataArray));
    }

    public function getParameterDataType($type, $isForDocumentation=false)
    {
        if ( 'array' === $type ) {
            return ClassDefinition::TYPE_ARRAY;
        }
        if ( 'bool' === $type || 'boolean' === $type ) {
            return ClassDefinition::TYPE_BOOLEAN;
        }

        if ( ! preg_match('/(string|integer|int)/', $type) && ! $isForDocumentation ) {
            $this->addExternalResource($type);

            return $this->getNamespaceBaseName($type);
        }

        return '';
    }

    public function getPassedParameter(array $dataArray)
    {
        if ( ! isset($dataArray['name']) ) {
            throw new Exception('name not found in ' . var_export($dataArray, true));
        }
        $name = $dataArray['name'];

        return $name;
    }

    protected function generateSettersAndGetters()
    {
        $properties = $this->classDefinition->getProperties();

        $settersAndGetters = [];

        foreach ($properties as $props) {
            $settersAndGetters[] = [
                'propertyName' => $this->getPropertyName($props),
                'passedParameter' => $this->getPassedParameter($props),
                'parameterDataType' => $this->getParameterDataType($props['type']),
            ];
        }

//        print_r($settersAndGetters);
//        die;

        return $settersAndGetters;
    }
}




















