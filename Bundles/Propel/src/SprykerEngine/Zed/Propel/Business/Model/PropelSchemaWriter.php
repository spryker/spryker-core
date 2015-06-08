<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class PropelSchemaWriter implements PropelSchemaWriterInterface
{

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * @param Filesystem $filesystem
     * @param $targetDirectory
     */
    public function __construct(Filesystem $filesystem, $targetDirectory)
    {
        $this->filesystem = $filesystem;
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param string $fileName
     * @param string $content
     */
    public function write($fileName, $content)
    {
        $this->filesystem->dumpFile(
            $this->targetDirectory . DIRECTORY_SEPARATOR . $fileName,
            $content
        );
    }


}
