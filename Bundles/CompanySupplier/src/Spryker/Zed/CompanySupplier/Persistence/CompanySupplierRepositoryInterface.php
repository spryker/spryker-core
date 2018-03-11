<?php


namespace Spryker\Zed\CompanySupplier\Persistence;


use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;

interface CompanySupplierRepositoryInterface
{
    public function getAllSuppliers(): array;

    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer;
}