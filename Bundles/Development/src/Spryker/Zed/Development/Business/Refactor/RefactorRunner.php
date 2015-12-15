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

}
