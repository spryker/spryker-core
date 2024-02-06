import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AgentBarComponent } from './agent-bar.component';

@NgModule({
    imports: [CommonModule],
    declarations: [AgentBarComponent],
    exports: [AgentBarComponent],
})
export class AgentBarModule {}
