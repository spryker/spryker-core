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

        if (isset($config['enabled']) && $config['enabled']) {
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
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->getFacade()->copySchemaFilesToTargetDirectory();

        $config = Config::get(PropelConstants::PROPEL);
        $command = 'vendor/bin/propel model:build --config-dir '
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

}
