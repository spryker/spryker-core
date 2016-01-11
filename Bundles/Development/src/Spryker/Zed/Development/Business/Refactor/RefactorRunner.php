<?php

namespace Spryker\Zed\Development\Business\Refactor;

class RefactorRunner
{

    /**
     * @var RefactorInterface[]
     */
    protected $refactorer = [];

    /**
     * @param RefactorInterface $refactorer
     *
     * @return self
     */
    public function addRefactorer(RefactorInterface $refactorer)
    {
        $this->refactorer[] = $refactorer;

        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        foreach ($this->refactorer as $refactorer) {
            $refactorer->refactor();
        }
    }

    /**
     * Detect the project's root from $argv param.
     *
     * @throws RefactorException
     *
     * @return string
     */
    public function getRoot() {
        if (empty($argv[1])) {
            throw new RefactorException('Please provide path to project that needs upgrading');
        }
        $root = rtrim($argv[1], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        return $root;
    }

}
