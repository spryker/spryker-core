<?php

namespace ProjectA\Queue;

interface ErrorHandlerInterface
{

    /**
     * @param \Exception $e
     * @return mixed
     */
    public function handleError(\Exception $e);

} 
