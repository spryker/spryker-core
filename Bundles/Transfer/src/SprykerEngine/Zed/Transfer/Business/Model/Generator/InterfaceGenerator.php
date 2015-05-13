<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class InterfaceGenerator implements GeneratorInterface
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

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;

        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(__DIR__ . self::TWIG_TEMPLATES_LOCATION);
        $this->twig = new \Twig_Environment($loader, []);
        $this->twig->addExtension(new TransferTwigExtensions());
    }

    /**
     * @param DefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition)
    {
        $twigData = $this->getTwigData($definition);
        $fileName = $definition->getName() . '.php';
        $fileContent = $this->twig->render('interface.php.twig', $twigData);

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0755, true);
        }
        file_put_contents($this->targetDirectory . $fileName, $fileContent);

        return $fileName;
    }

    /**
     * @param InterfaceDefinitionInterface $classDefinition
     *
     * @return array
     */
    public function getTwigData(InterfaceDefinitionInterface $classDefinition)
    {
        return [
            'name' => $classDefinition->getName(),
            'methods' => $classDefinition->getMethods()
        ];
    }

}
