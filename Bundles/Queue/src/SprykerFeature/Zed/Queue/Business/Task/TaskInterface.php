<?php

namespace ProjectA\Queue\Task;

use ProjectA\Queue\DataObject;

interface TaskInterface
{

    /**
     * @param DataObject $dataObject
     * @return DataObject
     */
    public function run(DataObject $dataObject);

    /**
     * @return string
     */
    public function getName();

}
