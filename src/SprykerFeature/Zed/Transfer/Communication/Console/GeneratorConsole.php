<?php

namespace SprykerFeature\Zed\Transfer\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SprykerFeature\Zed\Transfer\Business\Model\Generator\ClassCollectionManager;
use SprykerFeature\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use SprykerFeature\Zed\Transfer\Business\Model\External\Sofee\SofeeXmlParser;

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
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        echo '-transfer-';
        echo "\n";

        $this->manager = new ClassCollectionManager();

        $file = dirname(__DIR__) . '/Console/data/transfer.xml';

        $fileContent = file_get_contents($file);
//
        $xml = new SofeeXmlParser();
        $xml->parseString($fileContent);

        $this->xmlTree = $xml->getTree();

        foreach ($this->xmlTree['transfers']['transfer'] as $item) {
            $this->manager->setClassDefinition($item);
        }

        $defs = $this->manager->getCollections();

        $generator = new ClassGenerator();
        $generator->setTargetFolder(dirname(__DIR__) . '/target/');
        foreach ($defs as $classDefinition) {
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
