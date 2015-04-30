<?php

namespace SprykerEngine\Zed\Transfer\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassCollectionManager;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Zend\Config\Factory;

class GeneratorConsole extends Console
{

    const COMMAND_NAME = 'transfer:generate';

    protected $manager;

    protected $xmlTree;

    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
        ;
    }

    protected function getXmlDefinitions()
    {
        $directory = APPLICATION_VENDOR_DIR . 'spryker/';

        $bundlesList = scandir($directory);

        $definitions = [];

        foreach ($bundlesList as $bundle) {
            if ( '.' !== $bundle && '..' !== $bundle ) {
                $xmlDefinitionFile = $directory . $bundle . '/transferDefinitions.xml';
                if ( is_file($xmlDefinitionFile) ) {
                    $definitions[] = Factory::fromFile($xmlDefinitionFile, true)->toArray();
                }
            }
        }

        return $definitions;
    }

    /**
     * where transfer objects will be generated
     *
     * @return string
     */
    private function getGeneratedTargetFolder()
    {
        return APPLICATION_SOURCE_DIR . 'Generated/Shared/Transfer/';
    }

    /**
     * remove generated TransferObjects
     */
    private function removeGeneratedTransferObjects()
    {
        $finder = new Finder();
        $finder->files()->in($this->getGeneratedTargetFolder());

        foreach ($finder as $file) {
            unlink($file->getRealPath());
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $this->manager = new ClassCollectionManager();

        $xmlDefinitions = $this->getXmlDefinitions();

        if ( empty($xmlDefinitions) ) {
            throw new \Exception('No XML configuration transfer files found');
        }

        foreach ($xmlDefinitions as $configObject) {
            if ( isset($configObject['transfer'][0]) ) {
                foreach ($configObject['transfer'] as $trasnferArray) {
                    $this->manager->setClassDefinition($trasnferArray);
                }
            } else {
                $this->manager->setClassDefinition($configObject['transfer']);
            }
        }

        $definitions = $this->manager->getCollections();
        $generator = new ClassGenerator();
        $generator->setNamespace('Generated\Shared\Transfer');
        $generator->setTargetFolder($this->getGeneratedTargetFolder());

        $this->removeGeneratedTransferObjects($this->getGeneratedTargetFolder());

        foreach ($definitions as $classDefinition) {
            $phpCode = $generator->generateClass($classDefinition);
//echo $phpCode;
//die;
            if ( ! is_dir($generator->getTargetFolder()) ) {
                mkdir($generator->getTargetFolder(), 0755, true);
            }

            file_put_contents($generator->getTargetFolder() . $classDefinition->getClassName() . '.php', $phpCode);
            $output->writeln(sprintf('<info>%s.php</info> was generated', $classDefinition->getClassName()));
        }
    }
}
