import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-manage-order-stats-block',
    templateUrl: './manage-order-stats-block.component.html',
    styleUrls: ['./manage-order-stats-block.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-manage-order-stats-block',
    },
})
export class ManageOrderStatsBlockComponent {
}
