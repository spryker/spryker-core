import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-agent-user-list-table',
    templateUrl: './agent-user-list-table.component.html',
    styleUrls: ['./agent-user-list-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-agent-user-list-table' },
})
export class AgentUserListTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
