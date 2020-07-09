import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-layout-footer',
    templateUrl: './layout-footer.component.html',
    styleUrls: ['./layout-footer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class LayoutFooterComponent {
    todayDate = new Date();
}
