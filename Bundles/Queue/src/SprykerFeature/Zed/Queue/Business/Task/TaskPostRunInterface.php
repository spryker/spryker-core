<?php

namespace ProjectA\Queue\Task;

use ProjectA\Queue\DataObject;

interface TaskPostRunInterface
{

    /**
     * @param DataObject $dataObject
     * @return DataObject
     */
    public function postRun(DataObject $dataObject);

}
