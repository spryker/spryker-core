import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-layout-centered',
    templateUrl: './mp-layout-centered.component.html',
    styleUrls: ['./mp-layout-centered.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class MpLayoutCenteredComponent {
}
