import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-my-account',
    templateUrl: './my-account.component.html',
    styleUrls: ['./my-account.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-my-account' },
})
export class MyAccountComponent {}
