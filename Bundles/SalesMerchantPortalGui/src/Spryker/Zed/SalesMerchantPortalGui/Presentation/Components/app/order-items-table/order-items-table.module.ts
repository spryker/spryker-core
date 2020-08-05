import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';

import { OrderItemsTableComponent } from './order-items-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [OrderItemsTableComponent],
    exports: [OrderItemsTableComponent],
})
export class OrderItemsTableModule {
}
