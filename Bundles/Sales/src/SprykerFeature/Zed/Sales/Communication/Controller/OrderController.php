<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{

    const URL_PARAMETER_PROCESS = 'process';
    const URL_PARAMETER_STATUS = 'status';
    const URL_PARAMETER_AGE = 'age';

    /**
     * @var array
     */
    protected $filters = array(
        'process',
        'status',
        'age'
    );

    public function indexAction()
    {
        $grid = [
            'all' => [
                'columns' => [
                    'show' => [
                        'is_test'
                    ]
                ],
                'filter'  => []
            ],

            'real' => [
                'columns' => [
                    'hide' => [
                        'is_test'
                    ]
                ],
                'filter' => [
                    'logic'   => 'and',
                    'filters' => [
                        [
                            'field'    => 'is_test',
                            'operator' => 'eq',
                            'value'    => 0
                        ]
                    ]
                ]
            ],

            'test' => [
                'columns' => [
                    'hide' => [
                        'is_test'
                    ]
                ],
                'filter' => [
                    'logic'   => 'and',
                    'filters' => [
                        [
                            'field'    => 'is_test',
                            'operator' => 'eq',
                            'value'    => 1
                        ]
                    ]
                ]
            ]
        ];
        return $this->viewResponse([
            'all' => htmlentities(json_encode($grid['all'])),
            'real' => htmlentities(json_encode($grid['real'])),
            'test' => htmlentities(json_encode($grid['test']))
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function itemsAction(Request $request)
    {
        $filter = [];
        foreach ($this->filters as $filterName) {
            $filterValue = $request->query->get($filterName);
            if ($filterValue) {
                $filter[$filterName] = $filterValue;
            }
        }

        return $this->viewResponse([
            'filter' => $filter
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \ErrorException
     */
    public function markAsTestOrderAction(Request $request)
    {
        $orderId = $request->query->get('order_id', null);
        if ($orderId) {
            $orderEntity = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findPk($orderId);
            $this->facadeSales->markOrderAsTestOrder($orderEntity);
            $this->facadeSales->addNote('Marked order as test order', $orderEntity, true, 'manual action');
            $this->addMessageSuccess(__('Order marked as test order!'));
        }

        return $this->redirectResponse('/sales/order-details/index?id=' . $orderId);
    }


}
