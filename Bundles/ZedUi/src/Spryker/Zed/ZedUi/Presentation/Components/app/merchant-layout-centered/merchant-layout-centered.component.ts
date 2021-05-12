import { Component, ChangeDetectionStrategy, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-merchant-layout-centered',
    templateUrl: './merchant-layout-centered.component.html',
    styleUrls: ['./merchant-layout-centered.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class MerchantLayoutCenteredComponent {}
