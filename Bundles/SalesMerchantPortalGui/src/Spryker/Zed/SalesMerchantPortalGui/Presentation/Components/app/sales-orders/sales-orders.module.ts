import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { SalesOrdersComponent } from './sales-orders.component';
import { SalesOrdersTableModule } from '../sales-orders-table/sales-orders-table.module';

@NgModule({
    imports: [CommonModule, SalesOrdersTableModule, HeadlineModule],
    declarations: [SalesOrdersComponent],
    exports: [SalesOrdersComponent],
})
export class SalesOrdersModule {}
