<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Controller\Action;

use SprykerFeature\Shared\Library\Error\ErrorLogger;
use SprykerEngine\Shared\Transfer\AbstractTransfer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractGridController extends AbstractWidgetController
{

    const VIEW_TYPE = 'grid';

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        return $this->renderGridWidget($request);
    }

    /**
     * @var
     */
    protected $grid;

    /**
     * @param Request $request
     *
     * @return mixed|void
     */
    protected function initialize(Request $request)
    {
        $this->grid = $this->initializeGrid($request);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    abstract protected function initializeGrid(Request $request);

    /**
     * @return array
     */
    protected function renderGridWidget()
    {
        return $this->renderWidget(self::VIEW_TYPE, $this->grid);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $this->initialize($request);
        $gridRequest = $this->grid->getRequest();
        if ($gridRequest->isCreate()) {
            try {
                $ids = $this->handleCreateOrUpdateRequest($request);
                $returnData = $this->fetchReturnData($ids);

                return $this->sendGridResponse($this->grid, $returnData);
            } catch (\Exception $e) {
                ErrorLogger::log($e);

                return $this->sendGridError($this->grid, $e->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request)
    {
        $this->initialize($request);
        $gridRequest = $this->grid->getRequest();
        if ($gridRequest->isUpdate()) {
            try {
                $ids = $this->handleCreateOrUpdateRequest($request);
                $returnData = $this->fetchReturnData($ids);

                return $this->sendGridResponse($this->grid, $returnData);
            } catch (\Exception $e) {
                ErrorLogger::log($e);

                return $this->sendGridError($this->grid, $e->getMessage());
            }

        }
    }

    /**
     * @param Request $request
     *
     * @throws \ErrorException
     *
     * @return array
     */
    protected function handleCreateOrUpdateRequest(Request $request)
    {
        $collection = $this->createTransferCollection($request);
        if (($this->grid instanceof GridAbstract) && $this->grid->isValid($collection)) {
            $this->handleCreateOrUpdate($this->grid, $collection);
        }
        $ids = $this->extractResultIds($collection);

        return $ids;
    }

    /**
     * @param Request $request
     *
     * @return
     */
    protected function createTransferCollection(Request $request)
    {
        $gridRequest = $this->grid->getRequest();
        $parameters = $gridRequest->getParameters();
        $transferCollection = $this->loadTransferCollection();
        if ($gridRequest->isBatch()) {
            foreach ($parameters['models'] as $parameters) {
                $transfer = $this->createTransfer($parameters, $request);
                if ($transfer) {
                    $transferCollection->add($transfer);
                }
            }
        } else {
            $transfer = $this->createTransfer($parameters, $request);
            if ($transfer) {
                $transferCollection->add($transfer);
            }
        }

        return $transferCollection;
    }

    /**
     * @param array $parameters
     * @param Request $request
     *
     * @return AbstractTransfer
     */
    protected function createTransfer(array $parameters, Request $request)
    {
        /** @var AbstractTransfer $transfer */
        $transfer = $this->loadTransfer();
        $transfer->fromArray($parameters, true);

        return $transfer;
    }

    /**
     * @param $collection
     *
     * @return array
     */
    protected function extractResultIds($collection)
    {
        $result = [];
        $getterMethod = $this->grid->getGridIdFieldGetter();
        foreach ($collection as $item) {
            $result[] = $item->$getterMethod();
        }

        return $result;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function readAction(Request $request)
    {
        $this->initialize($request);
        $gridRequest = $this->grid->getRequest();

        return $this->sendGridResponse($this->grid);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroyAction(Request $request)
    {
        $this->initialize($request);
        $gridRequest = $this->grid->getRequest();
        if ($gridRequest->isDestroy()) {
            try {
                $result = $this->handleDestroyRequest($request);

                return $this->sendGridResponse($this->grid, $result);
            } catch (\Exception $e) {
                ErrorLogger::log($e);

                return $this->sendGridError($this->grid, $e->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     *
     * @throws \ErrorException
     *
     * @return array
     */
    protected function handleDestroyRequest(Request $request)
    {
        $this->initialize($request);
        $collection = $this->createTransferCollection($request);
        $this->handleDestroy($this->grid, $collection);
        $ids = $this->extractResultIds($collection);

        return $ids;
    }

    /**
     * @param $grid
     * @param null $result
     *
     * @return JsonResponse
     */
    protected function sendGridResponse($grid, $result = null)
    {
        $dataSource = $grid->getDataSource();

        $response = '';
        if ($grid->getRequest()->isRead()) {
            $response = $dataSource->createResponse();
        } else {
            if (!is_null($result)) {
                $response = $result;
            }
        }

        return new JsonResponse($response);
    }

    /**
     * @param $grid
     * @param $message
     *
     * @return JsonResponse
     */
    protected function sendGridError($grid, $message)
    {
        $error = new \StdClass();
        $error->status = 'error';
        $error->message = $message;

        http_response_code(500);

        return new JsonResponse((array) $error);
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    protected function fetchReturnData($ids)
    {
        if (!is_array($ids)) {
            $ids = (array) $ids;
        }
        $dataSource = $this->grid->getDataSource();
        $data = $dataSource->getDataByField($this->grid->getIdFieldName(), $ids);
        $returnData = ['data' => $data];
        if (($this->grid instanceof GridAbstract) && $this->grid->getErrors()) {
            $returnData['success'] = false;
            $returnData['messages'] = $this->grid->getErrors();
        }

        return $returnData;
    }

    /**
     * @return mixed
     */
    protected function loadTransferCollection()
    {
    }

    /**
     * @return AbstractTransfer
     */
    protected function loadTransfer()
    {
    }

    /**
     * @param $grid
     * @param $collection
     *
     * @throws \ErrorException
     */
    public function handleCreateOrUpdate($grid, $collection = null)
    {
        throw new \ErrorException('Modifying not allowed for this grid!');
    }

    /**
     * @param $grid
     * @param AbstractTransferCollection $collection
     *
     * @throws \ErrorException
     */
    public function handleDestroy($grid, AbstractTransferCollection $collection = null)
    {
        throw new \ErrorException('Deleting not allowed for this grid!');
    }

}
