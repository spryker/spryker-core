import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';

import { AgentLoginComponent } from './agent-login/agent-login.component';
import { AgentLoginModule } from './agent-login/agent-login.module';

@NgModule({
    imports: [WebComponentsModule.withComponents([AgentLoginComponent]), AgentLoginModule],
})
export class ComponentsModule {}
