import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-product-offer',
    templateUrl: './product-offer.component.html',
    styleUrls: ['./product-offer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None
})
export class ProductOfferComponent {
    @Input() tableConfig = {
        dataUrl: 'https://angular-recipe-24caa.firebaseio.com/data.json',
        columnsUrl: 'https://angular-recipe-24caa.firebaseio.com/col.json',
        selectable: true,
        fixHeader: '200px',
        rowActions: [
            { id: '1234', title: '123' },
            { id: '2345', title: '234' },
        ],
        pageSizes: [20, 40, 50],
    };
    @Input() title: string;
}
