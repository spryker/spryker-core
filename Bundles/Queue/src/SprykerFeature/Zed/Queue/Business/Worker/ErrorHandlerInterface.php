<?php

namespace SprykerFeature\Zed\Queue\Business\Worker;

interface ErrorHandlerInterface
{

    /**
     * @param \Exception $exception
     *
     * @return mixed
     */
    public function handleError(\Exception $exception);

}
