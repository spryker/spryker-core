import { ChangeDetectionStrategy, Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-change-password-overlay',
    templateUrl: './change-password-overlay.component.html',
    styleUrls: ['./change-password-overlay.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-change-password-overlay' },
})
export class ChangePasswordOverlayComponent {}
