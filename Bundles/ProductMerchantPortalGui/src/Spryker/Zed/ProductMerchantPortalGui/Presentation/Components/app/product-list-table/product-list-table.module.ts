import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProductListTableComponent } from './product-list-table.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [ProductListTableComponent],
    exports: [ProductListTableComponent],
})
export class ProductListTableModule {}
