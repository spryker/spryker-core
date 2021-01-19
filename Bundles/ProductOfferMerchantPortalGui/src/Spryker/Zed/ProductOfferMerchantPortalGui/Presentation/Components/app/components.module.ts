import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonModule, ButtonComponent, ButtonLinkModule, ButtonLinkComponent } from '@spryker/button';

import { OffersListComponent } from './offers-list/offers-list.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { EditOfferModule } from './edit-offer/edit-offer.module';
import { EditOfferComponent } from './edit-offer/edit-offer.component';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { ToggleModule, ToggleComponent } from '@spryker/toggle';
import { CardModule, CardComponent } from '@spryker/card';
import { SelectModule, SelectComponent } from '@spryker/select';
import { InputModule, InputComponent } from '@spryker/input';
import { CheckboxModule, CheckboxComponent } from '@spryker/checkbox';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';

@NgModule({
    imports: [
        ProductOfferModule,
        OffersListModule,
        ButtonModule,
        ButtonLinkModule,
        FormItemModule,
        CollapsibleModule,
        CardModule,
        DateRangePickerModule,
        CheckboxModule,
        InputModule,
        SelectModule,
        ToggleModule,
        ChipsModule,
        EditOfferModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        OffersListComponent,
        ProductOfferComponent,
        ButtonLinkComponent,
        EditOfferComponent,
        ButtonComponent,
        ChipsComponent,
        ToggleComponent,
        InputComponent,
        SelectComponent,
        CardComponent,
        DateRangePickerComponent,
        CheckboxComponent,
        CollapsibleComponent,
        FormItemComponent,
    ];
}
