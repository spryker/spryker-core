import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-auth-footer',
    templateUrl: './mp-auth-footer.component.html',
    styleUrls: ['./mp-auth-footer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class MpAuthFooterComponent {
    todayDate = new Date();
}
