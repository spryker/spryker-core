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
import { CustomElementModule } from '@spryker/web-components';

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
    protected components = [
        {
            selector: 'mp-profile',
            component: ProfileComponent,
        },
        {
            selector: 'spy-button',
            component: ButtonComponent,
        },
        {
            selector: 'spy-form-item',
            component: FormItemComponent,
        },
        {
            selector: 'spy-input',
            component: InputComponent,
        },
        {
            selector: 'spy-textarea',
            component: TextareaComponent,
        },
        {
            selector: 'spy-card',
            component: CardComponent,
        },
        {
            selector: 'spy-collapsible',
            component: CollapsibleComponent,
        },
        {
            selector: 'spy-toggle',
            component: ToggleComponent,
        },
        {
            selector: 'spy-select',
            component: SelectComponent,
        },
        {
            selector: 'spy-label',
            component: LabelComponent,
        },
        {
            selector: 'spy-tabs',
            component: TabsComponent,
        },
        {
            selector: 'spy-tab',
            component: TabComponent,
        },
    ];
}
