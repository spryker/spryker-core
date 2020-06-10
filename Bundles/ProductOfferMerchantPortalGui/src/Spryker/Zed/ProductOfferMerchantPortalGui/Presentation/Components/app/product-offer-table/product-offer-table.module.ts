import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProductOfferTableComponent } from './product-offer-table.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [ProductOfferTableComponent],
    exports: [ProductOfferTableComponent],
})
export class ProductOfferTableModule {
}
