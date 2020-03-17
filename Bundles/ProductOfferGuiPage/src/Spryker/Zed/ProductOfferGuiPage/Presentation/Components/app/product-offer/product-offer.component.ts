import { Component, Input } from '@angular/core';

@Component({
    selector: 'mp-product-offer',
    templateUrl: './product-offer.component.html',
    styleUrls: ['./product-offer.component.less']
})
export class ProductOfferComponent {
    @Input() tableConfig = '';
    @Input() title: string;
}
