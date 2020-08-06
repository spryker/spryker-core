import { ChangeDetectionStrategy, Component, ViewEncapsulation, Input } from '@angular/core';

@Component({
    selector: 'mp-manage-order-collapsible-totals',
    templateUrl: './manage-order-collapsible-totals.component.html',
    styleUrls: ['./manage-order-collapsible-totals.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class ManageOrderCollapsibleTotalsComponent {
    @Input() title: string;
    @Input() url: string;

    internalUrl?: string;
}
