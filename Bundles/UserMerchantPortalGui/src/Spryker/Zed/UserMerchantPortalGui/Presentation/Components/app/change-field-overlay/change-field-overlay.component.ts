import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-change-field-overlay',
    templateUrl: './change-field-overlay.component.html',
    styleUrls: ['./change-field-overlay.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-change-field-overlay' },
})
export class ChangeFieldOverlayComponent {}
