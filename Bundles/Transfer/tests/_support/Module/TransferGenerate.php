<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer\Module;

use Codeception\Configuration;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Psr\Log\NullLogger;
use Spryker\Zed\Transfer\Business\TransferFacade;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class TransferGenerate extends Module
{

    const TARGET_DIRECTORY = 'target_directory';

    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        if (!empty($config['enabled'])) {
            $this->generateTransferObjects();
        }
    }

    /**
     * @return void
     */
    private function generateTransferObjects()
    {
        $transferFacade = $this->getFacade();

        $this->copyFromTestBundle();

        $transferFacade->deleteGeneratedTransferObjects();
        $transferFacade->generateTransferObjects(new NullLogger());
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferFacade
     */
    private function getFacade()
    {
        return new TransferFacade();
    }

    /**
     * @return void
     */
    private function copyFromTestBundle()
    {
        $finder = $this->getBundleTransferSchemas();

        if ($finder->count() > 0) {
            $pathForTransferSchemas = $this->getTargetSchemaDirectory();
            $filesystem = new Filesystem();
            foreach ($finder as $file) {
                $path = $pathForTransferSchemas . 'Transfer' . DIRECTORY_SEPARATOR . $file->getFileName();
                $filesystem->dumpFile($path, $file->getContents());
            }
        }
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    private function getBundleTransferSchemas()
    {
        $testBundleSchemaDirectory = Configuration::projectDir() . DIRECTORY_SEPARATOR . 'src';
        $finder = new Finder();
        $finder->files()->in($testBundleSchemaDirectory)->name('*.transfer.xml');

        return $finder;
    }

    /**
     * @return string
     */
    private function getTargetSchemaDirectory()
    {
        $pathForTransferSchemas = APPLICATION_ROOT_DIR . '/src/Spryker/Shared/Testify/';

        if (isset($this->config[self::TARGET_DIRECTORY])) {
            $pathForTransferSchemas = $this->config[self::TARGET_DIRECTORY];
        }

        if (!is_dir($pathForTransferSchemas)) {
            mkdir($pathForTransferSchemas, 0775, true);
        }

        return $pathForTransferSchemas;
    }

}
