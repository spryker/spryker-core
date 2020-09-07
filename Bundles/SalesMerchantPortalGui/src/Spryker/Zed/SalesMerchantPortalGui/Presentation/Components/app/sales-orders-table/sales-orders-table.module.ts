import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';

import { SalesOrdersTableComponent } from './sales-orders-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [SalesOrdersTableComponent],
    exports: [SalesOrdersTableComponent],
})
export class SalesOrdersTableModule {}
