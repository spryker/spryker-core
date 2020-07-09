import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';

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
    @Input() name: string;
    @Input() info: string;
}
