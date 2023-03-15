import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-dummy-layout',
    templateUrl: './dummy-layout.component.html',
    styleUrls: ['./dummy-layout.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class DummyLayoutComponent {}
