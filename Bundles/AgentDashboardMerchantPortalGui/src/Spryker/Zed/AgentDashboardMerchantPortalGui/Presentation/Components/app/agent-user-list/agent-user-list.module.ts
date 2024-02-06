import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';

import { AgentUserListTableModule } from '../agent-user-list-table/agent-user-list-table.module';
import { AgentUserListComponent } from './agent-user-list.component';

@NgModule({
    imports: [CommonModule, AgentUserListTableModule, HeadlineModule],
    declarations: [AgentUserListComponent],
    exports: [AgentUserListComponent],
})
export class AgentUserListModule {}
