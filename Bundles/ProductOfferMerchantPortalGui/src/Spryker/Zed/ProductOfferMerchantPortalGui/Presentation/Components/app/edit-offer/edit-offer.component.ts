import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation, OnChanges, SimpleChanges } from '@angular/core';
import { Image } from '../image-slider/image-slider.component';
import { ToJson } from '@spryker/utils';

export interface ProductDetails {
    name: string;
    sku: string;
    validFrom: string | Date;
    validTo: string | Date;
    validFromTitle: string;
    validToTitle: string;
}

@Component({
    selector: 'mp-edit-offer',
    templateUrl: './edit-offer.component.html',
    styleUrls: ['./edit-offer.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-edit-offer',
    },
})
export class EditOfferComponent implements OnChanges {
    @Input() @ToJson() product?: ProductDetails;
    @Input() @ToJson() images?: Image[];
    @Input() productDetailsTitle?: string;
    @Input() productCardTitle?: string;

    validFrom?: string | Date;
    validTo?: string | Date;

    ngOnChanges(change: SimpleChanges) {
        if ('product' in change) {
            this.validFrom = this.product?.validFrom || '-';
            this.validTo = this.product?.validTo || '-';
        }
    }
}
