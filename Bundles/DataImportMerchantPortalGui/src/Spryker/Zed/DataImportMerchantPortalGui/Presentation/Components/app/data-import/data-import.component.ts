import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-data-import',
    templateUrl: './data-import.component.html',
    styleUrls: ['./data-import.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-data-import' },
})
export class DataImportComponent {}
