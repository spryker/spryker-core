import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-dashboard-stats',
    templateUrl: './dashboard-stats.component.html',
    styleUrls: ['./dashboard-stats.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-dashboard-stats',
    },
})
export class DashboardStatsComponent {
}
