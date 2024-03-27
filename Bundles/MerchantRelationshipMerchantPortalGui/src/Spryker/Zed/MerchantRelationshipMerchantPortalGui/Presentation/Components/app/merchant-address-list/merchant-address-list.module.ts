import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { CheckboxModule } from '@spryker/checkbox';
import { IconModule } from '@spryker/icon';
import { IconAddressModule } from '../../icons/address';
import { MerchantAddressListComponent } from './merchant-address-list.component';

@NgModule({
    imports: [CommonModule, CheckboxModule, IconAddressModule, IconModule],
    declarations: [MerchantAddressListComponent],
    exports: [MerchantAddressListComponent],
})
export class MerchantAddressListModule {}
