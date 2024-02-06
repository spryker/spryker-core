import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-agent-user-list',
    templateUrl: './agent-user-list.component.html',
    styleUrls: ['./agent-user-list.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class AgentUserListComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
