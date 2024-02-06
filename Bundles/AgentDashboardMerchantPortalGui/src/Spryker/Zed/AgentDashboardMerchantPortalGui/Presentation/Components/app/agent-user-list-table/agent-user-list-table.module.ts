import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';

import { AgentUserListTableComponent } from './agent-user-list-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [AgentUserListTableComponent],
    exports: [AgentUserListTableComponent],
})
export class AgentUserListTableModule {}
