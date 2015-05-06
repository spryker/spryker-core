<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Config\Definition\Exception\Exception;

class ClassGenerator
{

    const TWIG_TEMPLATES_LOCATION = '/Templates/';

    /**
     * @var string
     */
    protected $targetDirectory = null;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new \Twig_Environment($loader, []);
        $this->twig->addExtension(new TransferTwigExtensions());
    }

    /**
     * @param ClassDefinition $definition
     *
     * @return string
     */
    public function generateClass(ClassDefinition $definition)
    {
        $twigData = $this->getTwigData($definition);

        return $this->twig->render('class.php.twig', $twigData);
    }

    /**
     * @param ClassDefinitionInterface $classDefinition
     *
     * @return array
     */
    public function getTwigData(ClassDefinitionInterface $classDefinition)
    {
        return [
            'className' => $classDefinition->getName(),
            'uses' => $classDefinition->getUses(),
            'interfaces' => $classDefinition->getInterfaces(),
            'constructorDefinition' => $classDefinition->getConstructorDefinition(),
            'properties' => $classDefinition->getProperties(),
            'methods' => $classDefinition->getMethods()
        ];
    }

}
