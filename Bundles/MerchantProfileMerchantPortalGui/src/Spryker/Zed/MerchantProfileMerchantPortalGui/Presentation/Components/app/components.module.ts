import { NgModule } from '@angular/core';
import { CardComponent, CardModule } from '@spryker/card';
import { CollapsibleComponent, CollapsibleModule } from '@spryker/collapsible';
import { IconModule } from '@spryker/icon';
import { LabelComponent, LabelModule } from '@spryker/label';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { WebComponentsModule } from '@spryker/web-components';

import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProfileComponent } from './profile/profile.component';
import { ProfileModule } from './profile/profile.module';
import { ChipsModule, ChipsComponent } from '@spryker/chips';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            TabsComponent,
            ProfileComponent,
            CardComponent,
            CollapsibleComponent,
            LabelComponent,
            TabComponent,
            ChipsComponent,
        ]),
        CardModule,
        CollapsibleModule,
        IconModule,
        LabelModule,
        IconUnitedStatesModule,
        IconGermanyModule,
        TabsModule,
        ProfileModule,
        ChipsModule,
    ],
    providers: [],
    declarations: [],
})
export class ComponentsModule {}
