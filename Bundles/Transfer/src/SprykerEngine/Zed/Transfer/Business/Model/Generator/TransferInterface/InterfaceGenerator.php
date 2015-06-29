<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\GeneratorInterface;

class InterfaceGenerator implements GeneratorInterface
{

    const TWIG_TEMPLATES_LOCATION = '/../Templates/';

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
    }

    /**
     * @param DefinitionInterface|InterfaceDefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition)
    {
        $twigData = $this->getTwigData($definition);
        $fileName = $definition->getName() . '.php';
        $fileContent = $this->twig->render('interface.php.twig', $twigData);

        $targetDirectory = $this->targetDirectory . DIRECTORY_SEPARATOR . $definition->getBundle() . DIRECTORY_SEPARATOR;

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        file_put_contents($targetDirectory . $fileName, $fileContent);

        return $fileName;
    }

    /**
     * @param DefinitionInterface|InterfaceDefinitionInterface $classDefinition
     *
     * @return array
     */
    public function getTwigData(InterfaceDefinitionInterface $classDefinition)
    {
        return [
            'namespace' => $classDefinition->getNamespace(),
            'bundle' => $classDefinition->getBundle(),
            'name' => $classDefinition->getName(),
            'uses' => $classDefinition->getUses(),
            'methods' => $classDefinition->getMethods()
        ];
    }

}
