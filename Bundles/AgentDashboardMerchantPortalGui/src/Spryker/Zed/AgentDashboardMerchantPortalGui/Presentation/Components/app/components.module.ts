import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { WebComponentsModule } from '@spryker/web-components';

import { AgentBarComponent } from './agent-bar/agent-bar.component';
import { AgentBarModule } from './agent-bar/agent-bar.module';
import { AgentUserListComponent } from './agent-user-list/agent-user-list.component';
import { AgentUserListModule } from './agent-user-list/agent-user-list.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([AgentBarComponent, AgentUserListComponent, ButtonLinkComponent]),
        AgentBarModule,
        AgentUserListModule,
        ButtonLinkModule,
    ],
})
export class ComponentsModule {}
