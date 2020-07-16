import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';

@Component({
    selector: 'mp-dashboard-stats-block',
    templateUrl: './dashboard-stats-block.component.html',
    styleUrls: ['./dashboard-stats-block.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-dashboard-stats-block',
    },
})
export class DashboardStatsBlockComponent {
    @Input() name?: string;
    @Input() count?: string;
}
