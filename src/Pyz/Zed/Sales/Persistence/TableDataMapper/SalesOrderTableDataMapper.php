<?php

namespace Pyz\Zed\Sales\Persistence\TableDataMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderCollectionTransfer;

class SalesOrderTableDataMapper
{

    public function mapSalesOrderTableDataArrayToSalesOrderCollectionTransfer(array $data, SalesOrderCollectionTransfer $salesOrderCollectionTransfer): SalesOrderCollectionTransfer
    {
        foreach ($data as $salesOrderData) {
            $orderTransfer = new OrderTransfer();
            $orderTransfer->fromArray($salesOrderData, true);

            $salesOrderCollectionTransfer->addOrder($orderTransfer);
        }

        return $salesOrderCollectionTransfer;
    }
}
