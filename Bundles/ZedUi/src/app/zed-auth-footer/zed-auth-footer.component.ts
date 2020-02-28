import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'zed-auth-footer',
    templateUrl: './zed-auth-footer.component.html',
    styleUrls: ['./zed-auth-footer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ZedAuthFooterComponent {
}
