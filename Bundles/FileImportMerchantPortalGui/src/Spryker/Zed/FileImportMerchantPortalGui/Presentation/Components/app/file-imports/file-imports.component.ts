import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-file-imports',
    templateUrl: './file-imports.component.html',
    styleUrls: ['./file-imports.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class FileImportsComponent {
    @Input() tableConfig: TableConfig;
    @Input() tableId?: string;
}
