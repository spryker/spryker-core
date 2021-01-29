import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';

import { ProductOfferComponent } from './product-offer.component';
import { ProductOfferTableModule } from '../product-offer-table/product-offer-table.module';

@NgModule({
    imports: [CommonModule, ProductOfferTableModule, HeadlineModule],
    declarations: [ProductOfferComponent],
    exports: [ProductOfferComponent],
})
export class ProductOfferModule {}
