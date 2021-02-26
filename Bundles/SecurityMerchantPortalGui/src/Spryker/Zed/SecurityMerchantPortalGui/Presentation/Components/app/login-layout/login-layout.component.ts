import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-login-layout',
    templateUrl: './login-layout.component.html',
    styleUrls: ['./login-layout.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class LoginLayoutComponent {}
