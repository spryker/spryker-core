import { NgModule } from '@angular/core';
import { CommentsThreadComponent, CommentsThreadModule } from '@mp/comment-merchant-portal-gui';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { CardComponent, CardModule } from '@spryker/card';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { WebComponentsModule } from '@spryker/web-components';
import { MerchantAddressListComponent } from './merchant-address-list/merchant-address-list.component';
import { MerchantAddressListModule } from './merchant-address-list/merchant-address-list.module';
import { MerchantRelationEditComponent } from './merchant-relation-edit/merchant-relation-edit.component';
import { MerchantRelationEditModule } from './merchant-relation-edit/merchant-relation-edit.module';
import { MerchantRelationRequestComponent } from './merchant-relation-request/merchant-relation-request.component';
import { MerchantRelationRequestModule } from './merchant-relation-request/merchant-relation-request.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            MerchantRelationRequestComponent,
            MerchantRelationEditComponent,
            ChipsComponent,
            ButtonLinkComponent,
            CardComponent,
            MerchantAddressListComponent,
            CommentsThreadComponent,
        ]),
        MerchantRelationRequestModule,
        MerchantRelationEditModule,
        ChipsModule,
        ButtonLinkModule,
        CardModule,
        MerchantAddressListModule,
        CommentsThreadModule,
    ],
})
export class ComponentsModule {}
