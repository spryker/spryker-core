import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import {
    ButtonModule,
    ButtonComponent,
    ButtonLinkModule,
    ButtonLinkComponent,
} from '@spryker/button';

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
import {
    DateRangePickerModule,
    DateRangePickerComponent,
} from '@spryker/date-picker';
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
    protected components = [
        {
            selector: 'mp-offers-list',
            component: OffersListComponent,
        },
        {
            selector: 'mp-product-offer',
            component: ProductOfferComponent,
        },
        {
            selector: 'spy-button-link',
            component: ButtonLinkComponent,
        },
        {
            selector: 'mp-edit-offer',
            component: EditOfferComponent,
        },
        {
            selector: 'spy-button',
            component: ButtonComponent,
        },
        {
            selector: 'spy-chips',
            component: ChipsComponent,
        },
        {
            selector: 'spy-toggle',
            component: ToggleComponent,
        },
        {
            selector: 'spy-input',
            component: InputComponent,
        },
        {
            selector: 'spy-select',
            component: SelectComponent,
        },
        {
            selector: 'spy-card',
            component: CardComponent,
        },
        {
            selector: 'spy-date-range-picker',
            component: DateRangePickerComponent,
        },
        {
            selector: 'spy-checkbox',
            component: CheckboxComponent,
        },
        {
            selector: 'spy-collapsible',
            component: CollapsibleComponent,
        },
        {
            selector: 'spy-form-item',
            component: FormItemComponent,
        },
    ];
}
