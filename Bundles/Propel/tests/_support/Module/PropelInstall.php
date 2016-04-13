<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Propel\Module;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\PropelFacade;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class PropelInstall extends Module
{

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        if (!empty($config['enabled'])) {
            $this->initPropel();
        }
    }

    /**
     * @throws \Exception
     *
     * @return int
     */
    protected function initPropel()
    {
        $this->copyFromTestBundle();
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->getFacade()->copySchemaFilesToTargetDirectory();

        $config = [
            'propel' => Config::get(PropelConstants::PROPEL),
        ];

        $dsn = Config::get(PropelConstants::ZED_DB_ENGINE) . ':host=' . Config::get(PropelConstants::ZED_DB_HOST)
            . ';dbname=' . Config::get(PropelConstants::ZED_DB_DATABASE);

        $config['propel']['database']['connections']['default']['dsn'] = $dsn;
        $config['propel']['database']['connections']['default']['user'] = Config::get(PropelConstants::ZED_DB_USERNAME);
        $config['propel']['database']['connections']['default']['password'] = Config::get(PropelConstants::ZED_DB_PASSWORD);

        $config['propel']['database']['connections']['zed'] = $config['propel']['database']['connections']['default'];

        $json = json_encode($config, JSON_PRETTY_PRINT);

        $fileName = $config['propel']['paths']['phpConfDir']
            . DIRECTORY_SEPARATOR
            . 'propel.json';

        if (!is_dir(dirname($fileName))) {
            mkdir(dirname($fileName), 0775, true);
        }

        file_put_contents($fileName, $json);

        $config = Config::get(PropelConstants::PROPEL);
        $command = 'APPLICATION_ENV=' . APPLICATION_ENV
            . ' APPLICATION_STORE=' . APPLICATION_STORE
            . ' APPLICATION_ROOT_DIR=' . APPLICATION_ROOT_DIR
            . ' APPLICATION=' . APPLICATION
            . ' vendor/bin/propel model:build --config-dir '
            . $config['paths']['phpConfDir']
            . ' --schema-dir ' . $config['paths']['schemaDir'] . ' --disable-namespace-auto-package';

        $process = new Process($command, Configuration::projectDir());

        return $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    /**
     * @return \Spryker\Zed\Propel\Business\PropelFacade
     */
    private function getFacade()
    {
        return new PropelFacade();
    }

    /**
     * @return void
     */
    private function copyFromTestBundle()
    {
        $testBundleSchemaDirectory = Configuration::projectDir() . 'src/Spryker/Zed/*/Persistence/Propel/Schema';
        if (count(glob($testBundleSchemaDirectory)) === 0) {
            return;
        }

        $finder = $this->getBundlePersistenceSchemas($testBundleSchemaDirectory);

        if ($finder->count() > 0) {
            $pathForSchemas = $this->getTargetSchemaDirectory();
            $filesystem = new Filesystem();
            foreach ($finder as $file) {
                $path = $pathForSchemas . DIRECTORY_SEPARATOR . $file->getFileName();
                $filesystem->dumpFile($path, $file->getContents());
            }
        }
    }

    /**
     * @param string $testBundleSchemaDirectory
     *
     * @return \Symfony\Component\Finder\Finder
     */
    private function getBundlePersistenceSchemas($testBundleSchemaDirectory)
    {
        $finder = new Finder();
        $finder->files()->in($testBundleSchemaDirectory)->name('*.schema.xml');

        return $finder;
    }

    /**
     * @return string
     */
    private function getTargetSchemaDirectory()
    {
        $pathForSchemas = APPLICATION_ROOT_DIR . '/src/Spryker/Zed/Testify/Persistence/Propel/Schema';

        if (!is_dir($pathForSchemas)) {
            mkdir($pathForSchemas, 0775, true);
        }

        return $pathForSchemas;
    }

}
