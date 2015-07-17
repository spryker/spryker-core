<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

interface ErrorHandlerInterface
{

    /**
     * @param \Exception $exception
     */
    public function handleError(\Exception $exception);

}
