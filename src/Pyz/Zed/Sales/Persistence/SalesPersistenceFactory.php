<?php

namespace Pyz\Zed\Sales\Persistence;

use Pyz\Zed\Sales\Persistence\TableDataMapper\SalesOrderTableDataMapper;

class SalesPersistenceFactory extends \Spryker\Zed\Sales\Persistence\SalesPersistenceFactory
{
    public function createSalesOrderTableDataMapper(): SalesOrderTableDataMapper
    {
        return new SalesOrderTableDataMapper();
    }
}
