import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'zed-layout-centered',
    templateUrl: './zed-layout-centered.component.html',
    styleUrls: ['./zed-layout-centered.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ZedLayoutCenteredComponent {
}
