import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { CardComponent, CardModule } from '@spryker/card';
import { CollapsibleComponent, CollapsibleModule } from '@spryker/collapsible';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { IconModule } from '@spryker/icon';
import { InputComponent, InputModule } from '@spryker/input';
import { LabelComponent, LabelModule } from '@spryker/label';
import { SelectComponent, SelectModule } from '@spryker/select';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TextareaComponent, TextareaModule } from '@spryker/textarea';
import { ToggleComponent, ToggleModule } from '@spryker/toggle';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProfileComponent } from './profile/profile.component';
import { ProfileModule } from './profile/profile.module';

@NgModule({
    imports: [
        ButtonModule,
        CardModule,
        FormItemModule,
        InputModule,
        CollapsibleModule,
        IconModule,
        TextareaModule,
        ToggleModule,
        SelectModule,
        LabelModule,
        IconUnitedStatesModule,
        IconGermanyModule,
        TabsModule,
        ProfileModule,
    ],
    providers: [],
    declarations: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        TabsComponent,
        ProfileComponent,
        ButtonComponent,
        FormItemComponent,
        InputComponent,
        TextareaComponent,
        CardComponent,
        CollapsibleComponent,
        ToggleComponent,
        SelectComponent,
        LabelComponent,
        TabComponent,
    ];
}
