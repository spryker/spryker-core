import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { OffersListComponent } from './offers-list/offers-list.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { ProductOfferModule } from './product-offer/product-offer.module';

@NgModule({
    imports: [
        ProductOfferModule,
        OffersListModule,
    ],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-offers-list',
            component: OffersListComponent,
        },
        {
            selector: 'mp-product-offer',
            component: ProductOfferComponent,
        },
    ];
}
