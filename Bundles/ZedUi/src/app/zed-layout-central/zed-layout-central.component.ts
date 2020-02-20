import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'zed-layout-central',
    templateUrl: './zed-layout-central.component.html',
    styleUrls: ['./zed-layout-central.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.ShadowDom
})
export class ZedLayoutCentralComponent {
}
