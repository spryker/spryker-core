<?php

namespace SprykerFeature\Zed\Transfer\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SprykerFeature\Zed\Transfer\Business\Model\Generator\ClassCollectionManager;
use SprykerFeature\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use SprykerFeature\Zed\Transfer\Business\Model\External\Sofee\SofeeXmlParser;
use Zend\Config\Config;
use Zend\Config\Factory;


class GeneratorConsole extends Console
{

    const COMMAND_NAME = 'transfer:generate';

    protected $manager;

    protected $xmlTree;

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
        ;
    }

    protected function mergeAndGetBundlesXmlDefinitions()
    {
        $directory = APPLICATION_VENDOR_DIR . 'spryker/';

        $bundlesList = scandir($directory);

        $xmlDefinition = new Config([], true);

//        print_r($xmlDefinition);
//        die;

        foreach ($bundlesList as $bundle) {
            if ( '.' !== $bundle && '..' !== $bundle  ) {
//                print_r($bundle);
//                echo "\n";

                $xmlDefinitionFile = $directory . $bundle . '/transferDefinitions.xml';
//                print_r($xmlDefinitionFile);
//                echo "\n";
                if ( is_file($xmlDefinitionFile) ) {
                    var_dump($bundle);
//                    $newDefinition = Factory::fromFile($xmlDefinitionFile, true);
//                    $xmlDefinition->merge($newDefinition);
                }
//                return $navigationDefinition->toArray();
            }
        }

//        print_r($xmlDefinition);
die;
//        print_r($bundles);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = new ClassCollectionManager();

        $fileContent = $this->mergeAndGetBundlesXmlDefinitions();


        echo $fileContent;
        echo "\n";
        die;


        $file = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) . '/Console/data/transfer.xml';

        $fileContent = file_get_contents($file);



        $xml = new SofeeXmlParser();
        $xml->parseString($fileContent);

        $this->xmlTree = $xml->getTree();

        if ( isset($this->xmlTree['transfers']['transfer'][0]) ) {
            // will generate more classes
            foreach ($this->xmlTree['transfers']['transfer'] as $item) {
                $this->manager->setClassDefinition($item);
            }
        } else {
            // will generate only one class
            $this->manager->setClassDefinition($this->xmlTree['transfers']['transfer']);
        }

        $definitions = $this->manager->getCollections();
        $generator = new ClassGenerator();
        $generator->setTargetFolder(dirname(__DIR__) . '/target/');

        foreach ($definitions as $classDefinition) {
            $phpCode = $generator->generateClass($classDefinition);
            echo $phpCode;

            echo "\n";
die;
            $target = dirname(__DIR__) . '/Generated/';
            if ( ! is_dir($target) ) {
                mkdir($target, 0755, true);
            }
            file_put_contents($target . $classDefinition->getClassName() . '.php', $phpCode);

//            echo $phpCode . "\n\n";
        }

        //$this->locator->transfer()->facade;

    }
}
