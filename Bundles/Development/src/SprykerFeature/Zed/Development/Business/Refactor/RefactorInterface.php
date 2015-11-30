<?php

namespace SprykerFeature\Zed\Development\Business\Refactor;

interface RefactorInterface
{

    /**
     * @throws RefactorException
     *
     * @return void
     */
    public function refactor();

}
