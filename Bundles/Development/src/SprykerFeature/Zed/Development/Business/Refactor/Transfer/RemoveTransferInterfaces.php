<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\Refactor\Transfer;

use SprykerFeature\Zed\Development\Business\Refactor\AbstractRefactor;
use SprykerFeature\Zed\Development\Business\Refactor\Refactor;
use SprykerFeature\Zed\Development\Business\Refactor\RefactorException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class RemoveTransferInterfaces extends AbstractRefactor
{

    /**
     * @var array
     */
    protected $directories = [];

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * @throws RefactorException
     *
     * @return void
     */
    public function refactor()
    {
        $phpFiles = $this->getFiles($this->directories, '*.php');

        $filesystem = new Filesystem();

        foreach ($phpFiles as $phpFile) {

            $content = $phpFile->getContents();

            // Search for transfer interface usages
            $interfacesUsagePattern = '/use Generated\\\\Shared\\\\.*\\\\(.+)Interface;/';
            if (preg_match_all($interfacesUsagePattern, $content, $interfaceMatches, PREG_SET_ORDER) === 0) {
                continue;
            }

            foreach ($interfaceMatches as $match) {
                $this->replaceInterfaceUsages($match[1], $content, $phpFile);

                $this->replaceInterfaceTypeHints($match[1], $content, $phpFile);
            }

            $filesystem->dumpFile($phpFile->getPathname(), $content);
        }
    }

    /**
     * @param string $transferName
     * @param string $content
     * @param SplFileInfo $phpFile
     *
     * @throws RefactorException
     *
     * @return void
     */
    protected function replaceInterfaceUsages($transferName, &$content, SplFileInfo $phpFile)
    {
        $interfaceUsagePattern = '/use Generated\\\\Shared\\\\.*\\\\' . $transferName . 'Interface;\n/';
        $transferUsagePattern = '/use Generated\\\\Shared\\\\Transfer\\\\' . $transferName . 'Transfer;\n/';
        if (preg_match($transferUsagePattern, $content)) {
            $content = preg_replace($interfaceUsagePattern, '', $content);
        } else {
            $content = preg_replace($interfaceUsagePattern, 'use Generated\\Shared\\Transfer\\' . $transferName . "Transfer;\n", $content);
        }

        if ($content === null) {
            throw new RefactorException(sprintf(
                'Could not replace %s usage in file %s',
                $transferName,
                $phpFile->getRealPath()
            ));
        }
    }

    /**
     * @param string $transferName
     * @param string $content
     * @param SplFileInfo $phpFile
     *
     * @throws RefactorException
     *
     * @return mixed
     */
    protected function replaceInterfaceTypeHints($transferName, &$content, SplFileInfo $phpFile)
    {
        $content = preg_replace('/\b' . $transferName . 'Interface\b/', $transferName . 'Transfer', $content);

        if ($content === null) {
            throw new RefactorException(sprintf(
                'Could not replace %sInterface to %sTransfer in file %s',
                $transferName,
                $transferName,
                $phpFile->getRealPath()
            ));
        }
    }
}
