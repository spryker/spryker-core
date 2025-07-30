import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-file-import',
    templateUrl: './file-import.component.html',
    styleUrls: ['./file-import.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-file-import' },
})
export class FileImportComponent {}
