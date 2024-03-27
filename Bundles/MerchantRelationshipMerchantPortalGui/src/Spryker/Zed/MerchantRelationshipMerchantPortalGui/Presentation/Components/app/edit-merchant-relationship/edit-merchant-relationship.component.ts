import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-edit-merchant-relationship',
    templateUrl: './edit-merchant-relationship.component.html',
    styleUrls: ['./edit-merchant-relationship.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-edit-merchant-relationship',
    },
})
export class EditMerchantRelationshipComponent {}
