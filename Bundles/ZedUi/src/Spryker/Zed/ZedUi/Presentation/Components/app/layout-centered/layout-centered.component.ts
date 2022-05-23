import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-layout-centered',
    templateUrl: './layout-centered.component.html',
    styleUrls: ['./layout-centered.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-layout-centered',
    },
})
export class LayoutCenteredComponent {}
