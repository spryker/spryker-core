import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';

@Component({
    selector: 'mp-dashboard',
    templateUrl: './dashboard.component.html',
    styleUrls: ['./dashboard.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class DashboardComponent {
}
