import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';

import { ProductOfferComponent } from './product-offer.component';

@NgModule({
    imports: [CommonModule, TableModule, TableModule.forRoot()],
    declarations: [ProductOfferComponent],
    exports: [ProductOfferComponent],
})
export class ProductOfferModule {
}
