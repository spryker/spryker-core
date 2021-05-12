import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';

import { ProductListComponent } from './product-list.component';
import { ProductListTableModule } from '../product-list-table/product-list-table.module';

@NgModule({
    imports: [CommonModule, ProductListTableModule, HeadlineModule],
    declarations: [ProductListComponent],
    exports: [ProductListComponent],
})
export class ProductListModule {}
