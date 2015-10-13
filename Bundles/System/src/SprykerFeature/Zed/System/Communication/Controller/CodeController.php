<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class CodeController extends AbstractController
{

    public function checkTreeAction()
    {
    }

    protected function createFileList()
    {
    }

    public function facadeApiAction()
    {
        // TODO remove later
    }

    public function zedApiAction()
    {
        // TODO remove later
    }

    public function libraryApiAction()
    {
        // TODO remove later
    }

    public function gitLogAction()
    {
        // TODO remove later
    }

    /**
     * Just prototype - code (but it works)
     * Key is from Yves-Migusta!
     */
    public function relicAction()
    {
        $key = 'ad7e8294603a23290ca1f032649f242d7af5125a1058853';
        $application = '3072056';

        $echo = [];
        exec("curl -X GET 'https://api.newrelic.com/v2/applications/$application/metrics/data.json' -H 'X-Api-Key:$key' -i -d 'names[]=Apdex&summarize=false' ", $echo);
        $result = json_decode(end($echo));

        $data = [];
        foreach ($result->metric_data->metrics as $metric) {
            $i = 0;
            foreach ($metric->timeslices as $timeslice) {
                $dt = new \DateTime($timeslice->from);
                $from = $dt->format('H:i');

                $item = [
                    'from' => $from,
                    'value' => $timeslice->values->count,
                ];

                $i++;
                $data[] = $item;
            }
        }

        return $this->viewResponse([
            'chartData' => $data,
        ]);
    }

}
