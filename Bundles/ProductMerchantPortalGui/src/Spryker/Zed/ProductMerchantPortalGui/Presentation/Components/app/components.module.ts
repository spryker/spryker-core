import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonModule, ButtonComponent, ButtonLinkModule, ButtonLinkComponent } from '@spryker/button';

import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
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
        ProductListModule,
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
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ProductListComponent,
        ButtonLinkComponent,
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
