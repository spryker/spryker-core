import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';

import { AgentLoginComponent } from './agent-login.component';

@NgModule({
    imports: [CommonModule, LogoModule, CardModule],
    declarations: [AgentLoginComponent],
    exports: [AgentLoginComponent],
})
export class AgentLoginModule {}
