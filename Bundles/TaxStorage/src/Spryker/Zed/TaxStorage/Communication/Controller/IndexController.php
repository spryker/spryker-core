<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Communication\Controller;

use Generated\Shared\Transfer\TaxRateStorageTransfer;
use Generated\Shared\Transfer\TaxSetDataStorageTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepository getRepository()
 * @method \Spryker\Zed\TaxStorage\Communication\TaxStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacade getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {

        /** @var \Spryker\Client\TaxStorage\TaxStorageClient $client */
        $client = $this->getFactory()->getOwnClient();
        $taxSetStorageTransfer = $client->findTaxSetCollectionStorage(7);
        dump($taxSetStorageTransfer);
        exit('Finita la commedia!');

        $this->getFacade()->publishByTaxSetIds([10]);

        exit('sdcsdc');
//        $taxSetIds = $this->getRepository()->findTaxSetIdsByTaxRateIds([3]);
//        $taxSetIds = $this->getRepository()->findTaxSetsByIds([1]);
        $taxSetIds = $this->getRepository()->findTaxSetStoragesByIds([2]);

//        $this->getFactory()->getEm()->deleteTaxSetStorage([]);


        $taxSetTransfer = $taxSetIds[0];
        dump($taxSetTransfer);
        exit('Finita la commedia!');

        $taxSetStorageTransfer = new TaxSetStorageTransfer();
        $taxSetStorageTransfer->fromArray($taxSetTransfer->toArray(), true);
        $taxSetStorageTransfer->setFkTaxSet($taxSetTransfer->getIdTaxSet());
//        $this->getFactory()->getEm()->deleteTaxSetStorage($taxSetStorageTransfer);
//        exit('Finita la comedia!');

        $array = new \ArrayObject();
        foreach ($taxSetTransfer->getTaxRates() as $taxRate) {
            $entry = (new TaxRateStorageTransfer())->fromArray($taxRate->toArray(), true);
            $array->append($entry);
        }
        $taxSetDataTransfer = new TaxSetDataStorageTransfer();
        $taxSetDataTransfer->setTaxRates($array);
        $taxSetStorageTransfer->setData($taxSetDataTransfer);

        dump($taxSetStorageTransfer);


        $this->getFactory()->getEm()->saveTaxSetStorage($taxSetStorageTransfer);
        exit('Hello Spryker!');
    }
}