import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-file-imports-table',
    templateUrl: './file-imports-table.component.html',
    styleUrls: ['./file-imports-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-file-imports-table',
    },
})
export class FileImportsTableComponent {
    @Input() config: TableConfig;
    @Input() tableId?: string;
}
