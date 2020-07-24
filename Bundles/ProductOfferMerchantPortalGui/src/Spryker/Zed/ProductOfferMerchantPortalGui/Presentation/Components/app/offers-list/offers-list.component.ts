import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig } from '@spryker/table';

@Component({
    selector: 'mp-offers-list',
    templateUrl: './offers-list.component.html',
    styleUrls: ['./offers-list.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class OffersListComponent {
    @Input() tableConfig: TableConfig;
}
