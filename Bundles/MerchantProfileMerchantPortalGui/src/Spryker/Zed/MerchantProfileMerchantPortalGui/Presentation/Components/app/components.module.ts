import { NgModule } from '@angular/core';
import { CardComponent, CardModule } from '@spryker/card';
import { CollapsibleComponent, CollapsibleModule } from '@spryker/collapsible';
import { IconModule } from '@spryker/icon';
import { LabelComponent, LabelModule } from '@spryker/label';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProfileComponent } from './profile/profile.component';
import { ProfileModule } from './profile/profile.module';

@NgModule({
    imports: [
        CardModule,
        CollapsibleModule,
        IconModule,
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
        CardComponent,
        CollapsibleComponent,
        LabelComponent,
        TabComponent,
    ];
}
