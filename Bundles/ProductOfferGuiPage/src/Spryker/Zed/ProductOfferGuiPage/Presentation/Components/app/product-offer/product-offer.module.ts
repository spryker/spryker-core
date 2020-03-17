import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ProductOfferComponent } from './product-offer.component';

@NgModule({
    imports: [CommonModule],
    declarations: [ProductOfferComponent],
    exports: [ProductOfferComponent],
})
export class ProductOfferModule {
}
