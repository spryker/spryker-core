import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-auth-footer',
    templateUrl: './auth-footer.component.html',
    styleUrls: ['./auth-footer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class AuthFooterComponent {
    todayDate = new Date();
}
