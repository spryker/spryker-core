<?php

namespace SprykerFeature\Zed\Setup\Communication\Console\Propel;

use SprykerFeature\Shared\Library\Bundle\BundleConfig;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class SchemaCopyConsole extends Console
{

    const COMMAND_NAME = 'setup:propel:schema:copy';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Copies schema file from packages to generated folder');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->removeSchemas();
        $this->copySchemas();
    }

    private function removeSchemas()
    {
        $schemaDirectory = $this->getSchemaDir();
        if (is_dir($schemaDirectory)) {
            $this->info('Remove schemas');
            $finder = new Finder();
            $filesystem = new Filesystem();
            foreach ($finder->files()->in($schemaDirectory) as $schema) {
                $filesystem->remove($schema);
            }
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getSchemaDir()
    {
        $config = Config::get(SystemConfig::PROPEL);
        $schemaDir = $config['paths']['schemaDir'] . DIRECTORY_SEPARATOR;

        return $schemaDir;
    }

    private function copySchemas()
    {
        $this->info('Copy schemas');
        $schemaDir = $this->getSchemaDir();
        $activeSchemas = (new BundleConfig())->getActiveSchemas();

        $filesystem = new Filesystem();
        foreach ($activeSchemas as $schema) {
            $filesystem->copy($schema, $schemaDir . basename($schema));
        }
    }
}
