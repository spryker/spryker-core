<?php

namespace ProjectA\Queue\Task;

use ProjectA\Queue\DataObject;

interface TaskPreRunInterface
{

    /**
     * @param DataObject $dataObject
     * @return DataObject
     */
    public function preRun(DataObject $dataObject);

}
