import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-mfa-setup-table',
    templateUrl: './mfa-setup-table.component.html',
    styleUrls: ['./mfa-setup-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MfaSetupTableComponent {}
