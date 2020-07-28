import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { OfferOrdersComponent } from './offer-orders/offer-orders.component';
import { OfferOrdersModule } from './offer-orders/offer-orders.module';

@NgModule({
    imports: [OfferOrdersModule],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-offer-orders',
            component: OfferOrdersComponent,
        },
    ];
}
