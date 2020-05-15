import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { TableConfig, TableFeatureLocation } from '@spryker/table';
import { ToJson } from '@spryker/utils';

@Component({
    selector: 'mp-product-offer-table',
    templateUrl: './product-offer-table.component.html',
    styleUrls: ['./product-offer-table.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ProductOfferTableComponent {
    @Input() config: TableConfig;
    tableLocation = TableFeatureLocation;
}
